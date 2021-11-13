<?php

namespace Http;

class Request {
    /**
     * @var array|mixed|string
     */
    public $cookie;

    /**
     * @var array
     */
    public $request;

    /**
     * @var array|mixed|string
     */
    public $files;

    /**
     *
     */
    public function __construct()
    {
        $this->request = ($_REQUEST);
        $this->cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_FILES);
    }

    /**
     * @param string $key
     * @return array|mixed|string|null
     */
    public function get(string $key = '')
    {
        if ($key != '')
            return isset($_GET[$key]) ? $this->clean($_GET[$key]) : null;

        return $this->clean($_GET);
    }

    /**
     * @param string $key
     * @return array|mixed|string|null
     */
    public function post(string $key = '')
    {
        if ($key != '')
            return isset($_POST[$key]) ? $this->clean($_POST[$key]) : null;

        return $this->clean($_POST);
    }

    /**
     * @param string $key
     * @return array|mixed|string|null
     */
    public function input(string $key = '')
    {
        $post_data = file_get_contents("php://input");
        $request = json_decode($post_data, true);

        if ($key != '') {
            return isset($request[$key]) ? $this->clean($request[$key]) : null;
        }

        return ($request);
    }

    /**
     * @param string $key
     * @return array|mixed|string
     */
    public function server(string $key = '')
    {
        return isset($_SERVER[strtoupper($key)]) ? $this->clean($_SERVER[strtoupper($key)]) : $this->clean($_SERVER);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($this->server('REQUEST_METHOD'));
    }

    /**
     * @return array|mixed|string
     */
    public function getClientIp()
    {
        return $this->server('REMOTE_ADDR');
    }

    /**
     * @return array|mixed|string
     */
    public function getUrl()
    {
        return $this->server('REQUEST_URI');
    }

    /**
     * @param $data
     * @return array|mixed|string
     */
    private function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {

                unset($data[$key]);

                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }
}