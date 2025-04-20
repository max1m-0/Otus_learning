<?php

namespace Otus\Crm\Deal\Events;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Crm\Service\Container;

class OnBeforeCrmDealAdd
{

    /**
     * @param array $arFields - входящий массив параметров сделки
     * @return false | void | \Bitrix\Main\Error - false при запрете на создание, void при успехе
     * @throws LoaderException|ArgumentException
     */
    public static function execute(array &$arFields)
    {
        if (!Loader::includeModule('crm') || !Loader::includeModule('im'))
        {
            return;
        }
        $factory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
        $limit = 1;
        $stages = ['C1:LOSE', 'C1:APOLOGY', 'C1:WON'];
        $deal = $factory->getItems(
            [
                'filter' => [
                    '=UF_CRM_1744824780' => $arFields['UF_CRM_1744824780'],
                    '!@STAGE_ID' => $stages,
                ],
                'limit' => $limit,
            ],
        );
        if (!empty($deal))
        {
            $responsibleId = $deal[0]->get('ASSIGNED_BY_ID');
            $messageFields = [
                "NOTIFY_TITLE" => GetMessage("TITLE"),
                "TO_USER_ID" => $responsibleId,
                "MESSAGE" => GetMessage("MESSAGE"),
                "FROM_USER_ID" => $responsibleId,
                "NOTIFY_TYPE" => IM_NOTIFY_FROM,
                "NOTIFY_MODULE" => "main",
                "NOTIFY_EVENT" => "manage",
            ];

            \CIMNotify::Add($messageFields);
            $arFields['RESULT_MESSAGE'] = GetMessage("MESSAGE");
            return false;
        }

    }

}