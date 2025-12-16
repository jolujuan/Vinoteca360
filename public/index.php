<?php

use App\Kernel;

//Dotenv carga las variables de los ficheros .env
//Lo desactivamos en produccion, porque Railway define las variables globales
if (($_SERVER['APP_RUNTIME_ENV'] ?? null) === 'prod') {
    $_SERVER['APP_RUNTIME_OPTIONS']['disable_dotenv'] = true;
}

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool)$context['APP_DEBUG']);
};
