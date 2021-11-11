<?php

    $router->get('/test', 'auth@test');

    $router->get("/", function (){
        echo 'index test ';
    });
