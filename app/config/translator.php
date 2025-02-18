<?php

/**
 * Translator component configuration.
 *
 * @link https://spiral.dev/docs/advanced-i18n#configuration
 */
return [
    'locale' => env('LOCALE', 'en'),
    'fallbackLocale' => env('LOCALE', 'en'),
    'directory' => directory('locale'),
    'autoRegister' => env('DEBUG', true),
    'directories' => [
        directory('vendor') . 'spiral/validator/locale/ru'
    ],
    'domains' => [
        // by default we can store all messages in one domain
        'messages' => ['*'],
        'currency' => ['*'],
        'errors' => ['*'],
        'entity' => ['*'],
    ]
];
