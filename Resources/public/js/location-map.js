// DOC:
// http://dev.openlayers.org/

// ICONS:
// http://dev.openlayers.org/img/marker.png
// http://dev.openlayers.org/img/marker-blue.png
// http://dev.openlayers.org/img/marker-green.png
// http://dev.openlayers.org/img/marker-gold.png

// TODO:
// + wydzielenie zapisu i odcczytu loc dla wielu elementów
// + zapis stanu mapy
// + utworzenie porządnej klasy Map
// + obsługa markerów (usuwanie, dodawanie)
// + pobieranie współrzędnych po nazwie
// + dodanie mechanizmu wyboru właściwego

function MapStateStorage(callback, initialState) {

  callback = callback || function(value) {
    if(value !== null) {
      var val = JSON.stringify(value);

      console.log(JSON.stringify(this.fetch(), null, 4));
    }
  }

  this.state = initialState || {
    zoom: 5,
    center: {
      lat: 52.069167,
      lon: 19.480556
    },
    coords: []
  };

  this.getDefaultState = function () {
    return JSON.parse(JSON.stringify(this.state));
  };

  this.set = function (state) {
    this.store(state);
  };

  this.get = function () {
    return this.fetch() !== null ? this.fetch() : this.getDefaultState();
  };

  this.store = callback;

  this.fetch = function () {

    if(typeof this.state.center === 'undefined') {
      return null;
    }

    return this.state;
  };
}

/**
 * OpenLayers Map supportive class
 *
 * @param stateStorage A class to preserve map state (zoom, center, coords)
 * @param selector Selector where a map is to render.
 * @param singleMarker if set to true only one non temporary selector is created with click
 * @constructor
 */
function Map(stateStorage, selector, singleMarker) {

  selector = selector || 'map';
  singleMarker = singleMarker || false;

  this.selector = document.getElementById(selector);
  this.stateStorage = stateStorage;
  this.singleMarker = singleMarker;

  this.state = this.stateStorage.get();


  this.map = null;
  this.maxZoomLevel = 18;

  this.projections = {
    lonLat: new OpenLayers.Projection('EPSG:4326'), // Transform from WGS 1984
    spherical: new OpenLayers.Projection('EPSG:900913') // to Spherical Mercator Projection
  };

  this.layers = {
    mapnik: new OpenLayers.Layer.OSM('MAP'),
    markers: new OpenLayers.Layer.Markers('Markers')
  };

  this.iconUrls = {
    red: 'http://dev.openlayers.org/img/marker.png',
    blue: 'http://dev.openlayers.org/img/marker-blue.png',
    green: 'http://dev.openlayers.org/img/marker-green.png',
    gold: 'http://dev.openlayers.org/img/marker-gold.png'
  };

  this.defaultIcon = 'red';
  this.temporaryIcon = 'gold';

  this.layers.markers.events.register('click', this, function (e) {
    var layer = this.layers.markers;
    var element = e.path[1];
    var collection = e.element.childNodes;

    var iconUrl = element.getElementsByTagName('img')[0].getAttribute('src');

    for (var icon in this.iconUrls) {
      if (this.iconUrls.hasOwnProperty(icon) && this.iconUrls[icon] === iconUrl) {
        break;
      }
    }

    if (icon === this.defaultIcon) {
      for (var i in collection) {
        if (collection[i].id === element.id) {
          break;
        }
      }

      this.state.coords.splice(i, 1);
      this.layers.markers.removeMarker(this.layers.markers.markers[i]);
    }

    if (icon === this.temporaryIcon) {

      for (var i in collection) {
        if (collection[i].id === element.id) {
          break;
        }
      }

      this.layers.markers.clearMarkers();

      var temp = {
        lat: this.state.coords[i].lat,
        lon: this.state.coords[i].lon,
      };

      this.state.coords.splice(0, this.state.coords.length);

      this.addPoint(temp, this.defaultIcon);
    }

    this.saveState();
  });

  this.config = {
    controls: [
      new OpenLayers.Control.Navigation({
        documentDrag: true,
        dragPanOptions: {
          enableKinetic: true
        }
      }),
      new OpenLayers.Control.ArgParser(),
      new OpenLayers.Control.Attribution(),
      new OpenLayers.Control.ScaleLine({
        maxWidth: 200
      }),
      new OpenLayers.Control.DragPan(),
      new OpenLayers.Control.Zoom()
    ]
  };

  this.searchConf = {
    _this: this,
    async: false,
    url: null,
    afterSuccess: function(count) {
      console.log(count);
    },
    success: function (resp) {
      var _this = this._this;

      _this.clear();

      var count = 0;

      $(resp).each(function (i, e) {

        if(
          e.class === 'aeroway'
          || e.class === 'waterway'
          || e.class === 'railway'
          || e.class === 'shop'
          || e.class === 'building'
          || e.type  === 'postcode'
        ) {
          return;
        }

        var loc = {lat: e.lat, lon: e.lon};
        _this.addTemporaryPoint(loc, e.display_name);
        count++;
      });

      this.afterSuccess(_this.state.coords.length);
    }
  };

  this.search = function (name) {

    this.searchConf.url = 'http://nominatim.openstreetmap.org/search?format=json&q=' + name;

    $.ajax(this.searchConf);
  };

  this.clear = function (hard) {
    hard = hard || 'true';

    var temp = this.stateStorage.getDefaultState();

    if(hard === 'true') {
      this.state = temp;
    }
    else {
      this.state.coords = temp.coords;
    }

    var center = new OpenLayers.LonLat(this.state.center.lon, this.state.center.lat)
    .transform(this.projections.lonLat, this.projections.spherical);

    this.map.setCenter(center, this.state.zoom);

    this.layers.markers.clearMarkers();
  };

  this.renderState = function() {
    var center = new OpenLayers.LonLat(this.state.center.lon, this.state.center.lat)
    .transform(this.projections.lonLat, this.projections.spherical);


    this.map.setCenter(center, this.state.zoom);

    for (var i = 0; i < this.state.coords.length; i++) {
      var loc = this.state.coords[i];

      var icons = {
        default: this.defaultIcon,
        temp: this.temporaryIcon
      };

      var icon = icons[loc.type];

      this.addMarker(loc, icon);
    }
  };

  this.init = function () {
    this.map = new OpenLayers.Map(this.selector, this.config);

    for (var key in this.layers) {
      if (this.layers.hasOwnProperty(key)) {
        this.map.addLayer(this.layers[key]);
      }
    }

    this.renderState();

    OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {
      _this: this,
      defaultHandlerOptions: {
        single: true,
        double: false,
        pixelTolerance: 0,
        stopSingle: false,
        stopDouble: false
      },
      initialize: function (options) {
        this.handlerOptions = OpenLayers.Util.extend({}, this.defaultHandlerOptions);
        OpenLayers.Control.prototype.initialize.apply(this, arguments);
        this.handler = new OpenLayers.Handler.Click(this, {click: this.trigger}, this.handlerOptions);
      },
      trigger: function (e) {
        var sphericalLoc = this._this.map.getLonLatFromPixel(e.xy);
        var loc = sphericalLoc.transform(this._this.projections.spherical, this._this.projections.lonLat);

        if(this._this.singleMarker === true) {
          this._this.clear('false');
        }

        this._this.addPoint({lon: loc.lon, lat: loc.lat});
      }
    });

    var click = new OpenLayers.Control.Click();
    this.map.addControl(click);

    click.activate();

    this.map.events.register('moveend', this, function (e) {
      this.saveState();
    });

    this.map.events.register('zoomend', this, function (e) {
      this.saveState();
    });
  };

  this.saveState = function () {
    this.state.zoom = this.map.getZoom();
    var scenter = this.map.getCenter();
    var center = scenter.transform(this.projections.spherical, this.projections.lonLat);

    this.state.center.lat = center.lat;
    this.state.center.lon = center.lon;

    this.stateStorage.set(this.state);
  };

  this.getIcon = function (color) {
    color = color || this.defaultIcon;

    var url = this.iconUrls.red;

    if (this.iconUrls.hasOwnProperty(color)) {
      url = this.iconUrls[color];
    }
    var size = new OpenLayers.Size(21, 25);
    var offset = new OpenLayers.Pixel(-(size.w / 2), -size.h);

    return new OpenLayers.Icon(url, size, offset);
  };

  this.addMarker = function(loc, icon, title) {
    icon = icon || this.defaultIcon;
    title = title || null;

    var sloc = new OpenLayers.LonLat(loc.lon, loc.lat).transform(this.projections.lonLat, this.projections.spherical);
    var marker = new OpenLayers.Marker(sloc, this.getIcon(icon));

    if (title !== null) {
      marker.icon.imageDiv.title = title;
    }

    this.layers.markers.addMarker(marker);
  };

  this.addPoint = function (loc, icon, title) {

    if(false === loc.hasOwnProperty('type')) {
      loc.type = 'default';
    }

    this.addMarker(loc, icon, title);

    if(true === singleMarker) {
      this.state.coords = [loc];
    }
    else {
      this.state.coords.push(loc);
    }
    this.saveState();
  };

  this.addTemporaryPoint = function (loc, title) {
    title = title || null;

    var type = 'temp';

    this.addPoint(loc, this.temporaryIcon, title, type);
  };

  this.init();
}
