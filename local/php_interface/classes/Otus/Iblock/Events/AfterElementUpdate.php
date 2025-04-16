<?php

namespace Otus\Iblock\Events;

use Bitrix\Main\Loader;
use CIBlockSection;

class AfterElementUpdate extends CatalogElement
{
    /**
     * @param $arFields
     * @return void
     * @throws \Bitrix\Main\LoaderException
     * Функция обновляет подраздел в каталоге в разделе АВТОМОБИЛИ
     */
    public static function execute(&$arFields): void
    {
        if ((int)$arFields["IBLOCK_ID"] !== self::BRAND_IBLOCK_ID)
        {
            return;
        }
        Loader::includeModule("iblock");

        $elementId = $arFields["ID"];
        $newName = $arFields["NAME"];
        $active = $arFields["ACTIVE"];

        $res = CIBlockSection::GetList([], [
            "IBLOCK_ID" => self::CATALOG_IBLOCK_ID,
            "CODE" => "linked_" . $elementId
        ]);

        if ($section = $res->Fetch()) {
            $fields = [];
            if ($newName)
            {
                $fields = array_merge($fields, ["NAME" => $newName]);
            }
            if ($active)
            {
                $fields = array_merge($fields, ["ACTIVE" => $active]);
            }
            (new \CIBlockSection)->Update($section["ID"], $fields);
        }
    }
}