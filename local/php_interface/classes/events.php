<?php
use Bitrix\Main\EventManager;
use Otus\UserTypes;
use Otus\Crm\Deal;
use Otus\Iblock;
$eventManager = EventManager::getInstance();

$eventManager->AddEventHandler(
    'iblock',
    'onIBlockPropertyBuildList',
    [
        UserTypes\ListIblock::class,
        'GetUserTypeDescription'
    ]);
$eventManager->addEventHandler('crm', 'OnAfterCrmDealUpdate', [Deal\AfterUpdate::class, 'OnAfterCrmDealUpdateHandler']);
$eventManager->addEventHandler('rest', 'OnRestServiceBuildDescription', [Otus\Rest\Events::class, 'OnRestServiceBuildDescriptionHandler']);
EventManager::getInstance()->addEventHandler("iblock","OnAfterIBlockElementUpdate",[Iblock\AfterUpdate::class, "onAfterIBlockElementUpdateHandler"]);
