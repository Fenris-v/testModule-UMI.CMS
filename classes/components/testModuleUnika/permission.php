<?php

/**
 * Группы прав на функционал модуля
 */
$permissions = [
    /**
     * Гостевые права
     */
    'guest' => [
        'page',
        'pageslist',
        'objesctslist'
    ],

    /**
     * Админские права
     */
    'admin' => [
        'getdatasetconfiguration',
        'pages',
        'addpage',
        'editpage',
        'deletepages',
        'activity',
        'objects',
        'addobject',
        'editobject',
        'deleteobjects'
    ]
];