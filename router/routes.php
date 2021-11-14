<?php

    $router->post('/login', 'auth@login');
    $router->post('/register', 'auth@register');

    $router->get("/", function (){
        echo 'Welcome!';
    });
?>