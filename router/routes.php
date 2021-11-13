<?php

    $router->get('/login', 'auth@login');
    $router->get('/register', 'auth@register');

    $router->get("/", function (){
        echo 'Welcome!';
    });
?>