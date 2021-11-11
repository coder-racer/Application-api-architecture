<?php

use Core\Controller;

class ControllersAuth extends Controller
{
    public function test(){
        $this->model = $this->loadModel("auth");
        $data = $this->model->GetTestData();
        $response_data = ['data' => $data];

        $this->response->sendStatus(200);
        $this->response->setContent($response_data);
    }
}