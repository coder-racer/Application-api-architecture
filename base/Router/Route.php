<?php

namespace Router;

class Route
{
    private $method;

    private $pattern;

    private $callback;

    private $MethodList = ['GET', 'POST', 'PUT', 'DELETE', 'OPTION'];

    private function validateMethod(string $method) {
        if (in_array(strtoupper($method), $this->MethodList))
            return $method;
        throw new Exception('Invalid Method Name');
    }

    public function __construct(String $method, String $pattern, $callback) {
        $this->method = $this->validateMethod(strtoupper($method));
        $this->pattern = cleanUrl($pattern);
        $this->callback = $callback;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPattern() {
        return $this->pattern;
    }

    public function getCallback() {
        return $this->callback;
    }
}