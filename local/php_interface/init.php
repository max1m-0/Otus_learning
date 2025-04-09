<?php
define('DEBUG_FILE_NAME',$_SERVER["DOCUMENT_ROOT"] . '/otus/log/debug.txt');

if (file_exists(__DIR__ . '/classes/autoload.php')){
    require_once __DIR__ . '/classes/autoload.php';
}

if (file_exists(__DIR__ . '/../app/autoload.php')){
    require_once __DIR__ . '/../app/autoload.php';
}

if (file_exists(__DIR__ . '/vendor/autoload.php')){
    require_once __DIR__ . '/vendor/autoload.php';
}
if (file_exists(__DIR__ . '/classes/events.php')){
    require_once __DIR__ . '/classes/events.php';
}

//\Bitrix\Main\UI\Extension::load(['popup','crm.currency','timeman.custom']);