<?php

function autoload($class) {
    /*
     * Грязный хак, который нужно переписать.
     */
    $exceptions = [
        "R"
    ];
    if (in_array($class, $exceptions))
        return true;

    if(!strncmp("Model_", $class, strlen("Model_")))
        return true;

    //--------------------------
    $file = BASE. str_replace('\\', '/', $class) . '.php';

    if (file_exists($file))
        require_once $file;
    else
        echo "Error: " . $file . "<br>";
}

spl_autoload_register('autoload');

require_once BASE . "Utils/clean.php";