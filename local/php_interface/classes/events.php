<?php

use Bitrix\Main\EventManager;
use Otus\Iblock\Events;

$eventManager = EventManager::getInstance();

$eventManager->AddEventHandler(
    'iblock',
    'OnAfterIBlockElementAdd',
    [
        Events\AfterElementAdd::class,
        'execute',
    ],
);
$eventManager->AddEventHandler(
    'iblock',
    'OnAfterIBlockElementDelete',
    [
        Events\AfterElementDelete::class,
        'execute',
    ],
);
$eventManager->AddEventHandler(
    'iblock',
    'OnAfterIBlockElementUpdate',
    [
        Events\AfterElementUpdate::class,
        'execute',
    ],
);
