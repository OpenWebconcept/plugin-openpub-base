<?php

return [
    'indexables' => [
        'openpub-item'
    ],
    'postStatus' => [
        'publish'
    ],
    'language'   => 'dutch',
    'expire'     => [
        'offset' => '14d',
        'decay'  => 0.5,
    ],
    'search' => [
        'weight' => 2
    ]

];
