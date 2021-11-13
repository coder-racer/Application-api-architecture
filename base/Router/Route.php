<?php

namespace Router;

class Route
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var array|string|string[]
     */
    private $pattern;

    /**
     * @var
     */
    private $callback;

    /**
     * @var string[]
     */
    private $MethodList = ['GET', 'POST', 'PUT', 'DELETE', 'OPTION'];

    /**
     * @param string $method
     * @return string
     */
    private function validateMethod(string $method) {
        if (in_array(strtoupper($method), $this->MethodList))
            return $method;
        throw new Exception('Invalid Method Name');
    }

    /**
     * @param String $method
     * @param String $pattern
     * @param $callback
     */
    public function __construct(String $method, String $pattern, $callback) {
        $this->method = $this->validateMethod(strtoupper($method));
        $this->pattern = cleanUrl($pattern);
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @return array|string|string[]
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * @return mixed
     */
    public function getCallback() {
        return $this->callback;
    }
}