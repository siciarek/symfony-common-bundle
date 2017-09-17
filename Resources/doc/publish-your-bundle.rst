Jak opublikować własnego bundla z github.com
--------------------------------------------

Aby opublikować własnego bundla, którego mamy na github.com,
np. ``siciarek/symfony-common-bundle``, w taki sposób aby był możliwy do zainstalowania poleceniem:

.. code-block:: bash

    $ cd your/project/dir
    $ composer install siciarek/symfony-common-bundle

Musimy najpierw założyć konto w aplikacji ``Packagist`` http://packagist.com.

Kiedy mamy już swoje konto, należy:

    * zalogować na stronie http://packagist.com (można przez github.com OAuth)
    * wejść na stronę https://packagist.org/packages/submit
    * w pole ``Repository URL (Git/Svn/Hg)`` wpisać adres swojego repozytorium np. https://github.com/siciarek/symfony-common-bundle.
    * nacisnąć guzik ``Check``, poczekać (na guziku jest spinner ale widzialny dopiero po najechaniu na niego kursorem)
      aż napis na nim zmieni się na ``Submit``
    * nacisnąć guzik ``Submit`` i poczekać aż przekieruje nas na stronę pakietu, w opisywanym przypadku powinna to być:
      https://packagist.org/packages/siciarek/symfony-common-bundle

Pakiet powinien być również widoczny na stronach https://packagist.org/users/siciarek/profile/ i https://packagist.org/users/siciarek/packages/ .

Tak dodany pakiet nie jest niestety wrażliwy na nasze zmiany w repozytorium i w tej postaci aby zaktualizować pakiet aby
jego najnowsza wersja była dostępna po wykonaniu polecenia:

.. code-block:: bash

    $ composer update siciarek/symfony-common-bundle

Należałoby każdorazowo po wprowadzeniu zmian zakończonych komendą ``push`` whodzić na stronę pakietu
https://packagist.org/packages/siciarek/symfony-common-bundle i kikać guzik ``Update``.

Nie jest to zbyt wygodne, a na pytanie czy można to zautomatyzować odpowiedź brzmi: **TAK**. W tym celu należy:

    * wejść na stronę repozytorium https://github.com/siciarek/symfony-common-bundle
    * kliknąc taba ``Settings`` na górze strony.
    * z menu bocznego wybać ``Integrations & services`` i poczekać aż załaduje się strona``Installed GitHub Apps``.
    * kliknąć guzik ``Add service``, i z rozwijalnej listy wybrać ``Packagist``.
    * Po przejściu na stronę https://github.com/siciarek/symfony-common-bundle/settings/hooks/new?service=packagist
      w pole formularza ``Token`` wpisać token, który pobierzemy, po uprzednim zalogowaniu, ze strony https://packagist.org/profile/
      Token pojawi się po kliknięciu w ``Show API Token`` (pozostałe pola, jeżeli nie chcemy zmieniać domyślnych wartości zostawiamy
      puste).
    * klikamy ``Update service``.

Serwis ``Packagist`` powinny być widoczny na stronie https://github.com/siciarek/symfony-common-bundle/settings/installations
zaznaczony jako czynny.






