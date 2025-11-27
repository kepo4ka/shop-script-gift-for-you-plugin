<?php

return [
    'name' => 'Подарок для вас',
    'description' => 'Плагин добавляет страницу, где пользователь может получить случайный товар из заданного набора в подарок',
    'img' => 'img/icon.png',
    'vendor' => '1005778',
    'version' => '1.0.0',
    'frontend' => true,
    'handlers' => [
        'frontend_footer' => 'frontendFooter',
        'routing' => 'routing',
    ]
];

