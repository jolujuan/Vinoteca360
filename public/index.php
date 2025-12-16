<?php

use App\Kernel;

if (!isset($_SERVER['APP_ENV']) && !isset($_ENV['APP_ENV']) || (($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) === 'prod')) {
    $_SERVER['APP_RUNTIME_OPTIONS'] = [
        'disable_dotenv' => true,
    ];
}

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool)$context['APP_DEBUG']);
};
