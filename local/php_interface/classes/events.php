<?php

use Bitrix\Main\EventManager;
use Otus\Iblock\Events;
use Otus\Crm\Deal\Events\OnBeforeCrmDealAdd;

$eventManager = EventManager::getInstance();
### Iblock ###
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
### Crm ###
$eventManager->AddEventHandler(
    'crm',
    'OnBeforeCrmDealAdd',
    [
        OnBeforeCrmDealAdd::class,
        'execute',
    ],
);
