<?php

// Exemplo seguro de autoload:
function autoloadModels($class) {
    $paths = [
        __DIR__ . '/models/' . $class . '.php',
        __DIR__ . '/controllers/' . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}

// Registra o autoload apenas se a função existir
if (function_exists('autoloadModels')) {
    spl_autoload_register('autoloadModels');
} else {
    // Alternativa: usar autoload anônimo
    spl_autoload_register(function($class) {
        $paths = [
            __DIR__ . '/models/' . $class . '.php',
            __DIR__ . '/controllers/' . $class . '.php',
        ];
        foreach ($paths as $file) {
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    });
}