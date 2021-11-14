<?php

namespace Core;

class Controller
{
    /**
     * @var mixed
     */
    public $request;

    /**
     * @var
     */
    public $model;

    /**
     * @var mixed
     */
    public $response;

    /**
     *
     */
    public function __construct() {
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
    }

    /**
     * @param $model
     * @return mixed
     */
    public function loadModel($model) {
        $file = MODELS . ucfirst($model) . '.php';

        // check exists file
        if (file_exists($file)) {
            require_once $file;

            $model = 'Models' . str_replace('/', '', ucwords($model, '/'));
            // check class exists
            if (class_exists($model))
                return new $model;
            else
                throw new Exception(sprintf('{ %s } this model class not found', $model));
        } else {
            throw new Exception(sprintf('{ %s } this model file not found', $file));
        }
    }

    /**
     * @param $msg
     * @param int $status
     */
    public function send($msg, $status = 200) {
        $this->response->setHeader(sprintf('HTTP/1.1 ' . $status . ' %s' , $this->response->getStatusCodeText($status)));
        $this->response->setContent($msg);
    }
}