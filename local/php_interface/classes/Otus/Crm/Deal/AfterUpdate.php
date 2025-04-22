<?php

namespace Otus\Crm\Deal;
use Bitrix\Main\Loader;
Loader::includeModule("crm");
Loader::includeModule("iblock");

class AfterUpdate
{
    public static function OnAfterCrmDealUpdateHandler($arFields)
    {
        $dealId = $arFields["ID"];

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => 76, "PROPERTY_DEAL_ID" => $dealId],
            false,
            false,
            ["ID"]
        );

        if ($el = $res->Fetch()) {
            $elementId = $el["ID"];
            \CIBlockElement::SetPropertyValuesEx($elementId, 76, [
                "SUMM" => $arFields["OPPORTUNITY"],
                "RESPONSIBLE_ID" => $arFields["ASSIGNED_BY_ID"],
            ]);
        }
    }
}
