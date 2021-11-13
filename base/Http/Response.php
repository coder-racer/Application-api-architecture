<?php

namespace Http;

class Response
{
    /**
     *
     */
    const MIN_STATUS_CODE = 100;
    /**
     *
     */
    const MAX_STATUS_CODE = 600;
    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var
     */
    protected $content;

    /**
     * @param String $header
     */
    public function setHeader(String $header) {
        $this->headers[] = $header;
    }

    /**
     * @return array
     */
    public function getHeader() {
        return $this->headers;
    }

    /**
     * @param $content
     */
    public function setContent($content) {
        $this->content = json_encode($content);
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param $url
     */
    public function redirect($url) {
        if (empty($url)) {
            trigger_error('Cannot redirect to an empty URL.');
            exit;
        }

        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&','', ''), $url), true, 302);
        exit();
    }

    /**
     * @param int $statusCode
     * @return bool
     */
    public function isInvalid(int $statusCode) : bool {
        return $statusCode < self::MIN_STATUS_CODE || $statusCode >= self::MAX_STATUS_CODE;
    }

    /**
     * @param $code
     */
    public function sendStatus($code) {
        if (!$this->isInvalid($code)) {
            $this->setHeader('HTTP/1.1 ' . $code );
        }
    }

    /**
     *
     */
    public function render() {
        if ($this->content) {
            $output = $this->content;

            if (!headers_sent()) {
                foreach ($this->headers as $header) {
                    header($header, true);
                }
            }

            echo $output;
        }
    }
}