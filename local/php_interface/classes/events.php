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
\Bitrix\Main\UI\Extension::load(['popup']);
\Bitrix\Main\Page\Asset::getInstance()->addJs('/local/js/timeman/custom/main.js');
