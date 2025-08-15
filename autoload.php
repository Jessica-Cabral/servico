<?php

spl_autoload_register(function ($className) {
    // Verifica se $className não está vazio
    if (empty($className)) {
        return false;
    }

    // Diretórios onde procurar classes (relativos à raiz do projeto)
    $directories = [
        'models/',
        'controllers/',
        'config/',
        'view/',
        'helpers/',
        'interfaces/',
        'traits/'
    ];

    // Sufixos de arquivo a tentar
    $suffixes = [
        'Class.php',
        '.class.php',
        'Controller.php',
        '.controller.php',
        '.php'
    ];

    foreach ($directories as $directory) {
        foreach ($suffixes as $suf) {
            $file = __DIR__ . DIRECTORY_SEPARATOR . $directory . $className . $suf;
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
    }

    // Não encontrado
    return false;
});
