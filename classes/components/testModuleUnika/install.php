<?php
/** Установщик модуля */

/**
* @var array $INFO реестр модуля
*/
$INFO = [
    'name' => 'testModuleUnika', // Имя модуля
    'config' => '1', // У модуля есть настройки
    'default_method' => 'page', // Метод по умолчанию в клиентской части
    'default_method_admin' => 'pages', // Метод по умолчанию в административной части
    'func_perms' => 'Группы прав на функционал модуля', // Группы прав
    'func_perms/guest' => 'Гостевые права', // Гостевая группа прав
    'func_perms/admin' => 'Административные права', // Административная группа прав
    'paging/' => 'Настройки постраничного вывода', // Группа настроек
    'paging/pages' => 10, // Настройка количества выводимых страниц
    'paging/objects' => 10 // Настройка количества выводимых объектов
];

/**
* @var array $COMPONENTS файлы модуля
*/
$COMPONENTS = [
    './classes/components/testModuleUnika/admin.php',
    './classes/components/testModuleUnika/class.php',
    './classes/components/testModuleUnika/customAdmin.php',
    './classes/components/testModuleUnika/macros.php',
    './classes/components/testModuleUnika/customMacros.php',
    './classes/components/testModuleUnika/lang.php',
    './classes/components/testModuleUnika/i18n.php',
    './classes/components/testModuleUnika/permission.php'
];