<?php
use Bitrix\Main\EventManager;
use Otus\UserTypes;
$eventManager = EventManager::getInstance();

$eventManager->AddEventHandler(
'iblock',
    'onIBlockPropertyBuildList',
    [
        UserTypes\ListIblock::class,
        'GetUserTypeDescription'
    ]);