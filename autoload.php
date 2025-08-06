<?php

spl_autoload_register(function ($className) {
    // Define os diretórios para procurar as classes
    $directories = [
        'models/',
        'controllers/',
        'helpers/',
        'interfaces/',
        'traits/'
    ];

    // Remove 'Controller' ou 'Model' do nome se existir
    $classFile = str_replace(['Controller', 'Model'], '', $className);
    
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    return false;
});