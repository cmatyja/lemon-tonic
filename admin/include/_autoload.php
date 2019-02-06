<?php
spl_autoload_register(function ($className) {
    $url = dirname(__DIR__) . '/class/' . $className . '.php';
    if (file_exists($url)) {
        require_once $url;
    } else {
        echo $url;
    }
});