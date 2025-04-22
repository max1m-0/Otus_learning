<?php

namespace Otus\Iblock;

use Bitrix\Crm\Service\Container;
use Bitrix\Main\Loader;

Loader::includeModule("crm");
Loader::includeModule("iblock");

class AfterUpdate
{
    public static function onAfterIBlockElementUpdateHandler($arFields)
    {
        $elementId = (int)$arFields["ID"];
        $iblockId = (int)$arFields["IBLOCK_ID"];

        $props = [];
        $res = \CIBlockElement::GetProperty($iblockId, $elementId, [], []);
        while ($prop = $res->Fetch()) {
            $props[$prop['CODE']] = $prop['VALUE'];
        }

        $dealId = (int)$props['DEAL_ID'];
        $summ = (float)$props['SUMM'];
        $responsibleId = (int)$props['RESPONSIBLE_ID'];

        if ($dealId)
        {
            $factory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
            $existingItem = $factory->getItem($dealId);
            if ($existingItem){
                file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/iblock_data.txt', print_r($existingItem->get("OPPORTUNITY"), true), FILE_APPEND);

                $existingItem->set("OPPORTUNITY",$summ);
                $existingItem->set("ASSIGNED_BY_ID",$responsibleId);
                $result = $existingItem->save();
                if ($result->isSuccess())
                file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/local/iblock_data.txt', print_r('suc', true), FILE_APPEND);

            }
        }
    }
}
