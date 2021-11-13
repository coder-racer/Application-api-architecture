<?php

namespace Router;

class Router
{
    /**
     * @var array
     */
    private $router = [];

    /**
     * @var array
     */
    private $matchRouter = [];

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var mixed
     */
    private $response;

    /**
     * @param string $url
     * @param string $method
     */
    public function __construct(string $url, string $method) {
        $this->url = rtrim($url, '/');
        $this->method = $method;

        // get response class of $GLOBALS var
        $this->response = $GLOBALS['response'];
    }

    /**
     * @param $pattern
     * @param $callback
     */
    public function get($pattern, $callback) {
        $this->addRoute("GET", $pattern, $callback);
    }

    /**
     * @param $pattern
     * @param $callback
     */
    public function post($pattern, $callback) {
        $this->addRoute('POST', $pattern, $callback);
    }

    /**
     * @param $pattern
     * @param $callback
     */
    public function put($pattern, $callback) {
        $this->addRoute('PUT', $pattern, $callback);
    }

    /**
     * @param $pattern
     * @param $callback
     */
    public function delete($pattern, $callback) {
        $this->addRoute('DELETE', $pattern, $callback);
    }

    /**
     * @param $method
     * @param $pattern
     * @param $callback
     */
    public function addRoute($method, $pattern, $callback) {
        array_push($this->router, new Route($method, $pattern, $callback));
    }

    /**
     *
     */
    private function getMatchRoutersByRequestMethod() {
        foreach ($this->router as $value) {
            if (strtoupper($this->method) == $value->getMethod())
                array_push($this->matchRouter, $value);
        }
    }

    /**
     * @param $pattern
     */
    private function getMatchRoutersByPattern($pattern) {
        $this->matchRouter = [];
        foreach ($pattern as $value) {
            if ($this->dispatch(cleanUrl($this->url), $value->getPattern()))
                array_push($this->matchRouter, $value);
        }
    }

    /**
     * @param $uri
     * @param $pattern
     * @return bool
     */
    public function dispatch($uri, $pattern) {
        $parsUrl = explode('?', $uri);
        $url = $parsUrl[0];

        preg_match_all('@:([\w]+)@', $pattern, $params, PREG_PATTERN_ORDER);

        $patternAsRegex = preg_replace_callback('@:([\w]+)@', [$this, 'convertPatternToRegex'], $pattern);

        if (substr($pattern, -1) === '/' ) {
            $patternAsRegex = $patternAsRegex . '?';
        }
        $patternAsRegex = '@^' . $patternAsRegex . '$@';

        if (preg_match($patternAsRegex, $url, $paramsValue)) {
            array_shift($paramsValue);
            foreach ($params[0] as $key => $value) {
                $val = substr($value, 1);
                if ($paramsValue[$val]) {
                    $this->setParams($val, urlencode($paramsValue[$val]));
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * @param $key
     * @param $value
     */
    private function setParams($key, $value) {
        $this->params[$key] = $value;
    }

    /**
     * @param $matches
     * @return string
     */
    private function convertPatternToRegex($matches) {
        $key = str_replace(':', '', $matches[0]);
        return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
    }

    /**
     *
     */
    public function run() {
        if (!is_array($this->router) || empty($this->router))
            throw new Exception('NON-Object Route Set');

        $this->getMatchRoutersByRequestMethod();
        $this->getMatchRoutersByPattern($this->matchRouter);

        if (!$this->matchRouter || empty($this->matchRouter)) {
            $this->sendNotFound();
        } else {
            // call to callback method
            if (is_callable($this->matchRouter[0]->getCallback()))
                call_user_func($this->matchRouter[0]->getCallback(), $this->params);
            else
                $this->runController($this->matchRouter[0]->getCallback(), $this->params);
        }
    }

    /**
     * @param $controller
     * @param $params
     * @return false|mixed|void
     */
    private function runController($controller, $params) {
        $parts = explode('@', $controller);
        $file = CONTROLLERS . ucfirst($parts[0]) . '.php';
       // echo $file;
        if (file_exists($file)) {
            require_once($file);

            $controller = 'Controllers' . ucfirst($parts[0]);

            if (class_exists($controller))
                $controller = new $controller();
            else
                $this->sendNotFound();

            if (isset($parts[1])) {
                $method = $parts[1];

                if (!method_exists($controller, $method))
                    $this->sendNotFound();

            } else {
                $method = 'index';
            }

            if (is_callable([$controller, $method]))
                return call_user_func([$controller, $method], $params);
            else
                $this->sendNotFound();
        }
    }

    /**
     *
     */
    private function sendNotFound() {
        $this->response->sendStatus(404);
        $this->response->setContent(['error' => 'Sorry This Route Not Found !', 'status_code' => 404]);
    }
}