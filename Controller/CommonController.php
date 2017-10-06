<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CommonController
 * @package AppBundle\Controller
 */
abstract class CommonController extends Controller
{
    const MESSAGE_ERROR = 'error';
    const MESSAGE_WARNING = 'warning';
    const MESSAGE_INFO = 'info';
    const MESSAGE_SUCCESS = 'success';

    /**
     * @var array list of custom Exception classnames, to propagate their messages in prod environment.
     */
    static $customExceptions = [];

    /**
     * Get route and params, to be called in any controller action.
     *
     * @return \stdClass
     */
    public function getRouteAndParams()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $routing = $request->attributes->all();

        $ret = new \stdClass();

        $ret->route = $routing['_route'];
        $ret->params = array_merge($routing['_route_params'], $request->query->all());

        return $ret;
    }

    /**
     * Returns json data from http request.
     *
     * @param boolean $array if true returns array \stdClass object otherwise.
     * @return array|\stdClass
     */
    protected function getJsonRequest($array = true)
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $input = $request->get('json');

        if ($input === null) {
            $input = file_get_contents('php://input');
        }

        $data = json_decode($input, $array);

        if (json_last_error() !== JSON_ERROR_NONE) {

            $frame = [
                'message' => json_last_error_msg(),
                'code' => 500,
                'data' => $input,
            ];

            $data = json_decode(json_encode($frame), $array);
        }

        return $data;
    }

    /**
     * Returns http response JSON formatted. JSONP is supported.
     *
     * @param array|\stdClass|string $data
     * @return Response
     */
    protected function getJsonResponse($data)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $json = json_encode($data, JSON_PRETTY_PRINT);

        $contentType = 'application/json';
        $content = $json;

        // <jsonp>
        $callback = $request->get('callback');

        if ($callback !== null) {
            $contentType = 'application/javascript';
            $content = sprintf('%s(%s);', $callback, $json);
        }
        // </jsonp>

        $response = new Response($content, 200, ['Content-Type' => $contentType]);

        return $response;
    }

    /**
     * Handle json action with callable.
     *
     * @param callable $run callable
     * @return Response
     */
    protected function handleJsonAction($run)
    {
        try {
            $frame = $run();
        } catch (\Exception $e) {

            $frame = ['message' => 'Unexpected Exception.', 'code' => 500];

            if (in_array(get_class($e), self::$customExceptions)) {
                $frame = ['message' => $e->getMessage(), 'code' => $e->getCode()];
            }

            $frame['data'] = [
                'code' => $e->getCode(),
            ];

            if ($this->get('kernel')->getEnvironment() != 'prod') {
                $frame = ['message' => $e->getMessage(), 'code' => $e->getCode()];
                $frame['data'] = [
                    'class' => get_class($e),
                    'trace' => $e->getTrace(),
                ];
            }
        }

        return $this->getJsonResponse($frame);
    }

    /**
     * Handle html action with callable
     *
     * @param callable $run callable
     * @param null|string $successMessage if != null flash success message is created.
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleHtmlAction($run, $successMessage = null)
    {
        $url = null;
        $request = $this->get('request_stack')->getCurrentRequest();

        try {
            $url = $run();

            if ($successMessage !== null) {
                $this->addFlash(self::MESSAGE_SUCCESS, $successMessage);
            }

        } catch (\Exception $e) {

            $type = self::MESSAGE_ERROR;

            if (in_array(get_class($e), self::$customExceptions)) {
                $type = self::MESSAGE_WARNING;
                $msg = $e->getMessage();
            } else {
                $msg = 'Unexpected Exception.';
            }

            $msg = $this->get('kernel')->getEnvironment() !== 'prod'
                ? $e->getMessage()."\n\n".$e->getTraceAsString()
                : $msg;

            $this->addFlash($type, $msg);
        }

        $referer = $request->headers->get('referer');

        $url = empty($url) ? $referer : $url;

        if ($url === null) {
            $url = $request->getSchemeAndHttpHost();
        }

        return $this->redirect($url);
    }
}
