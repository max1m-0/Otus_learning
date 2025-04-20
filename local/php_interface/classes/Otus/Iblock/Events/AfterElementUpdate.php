<?php

namespace Otus\Iblock\Events;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CIBlockSection;

class AfterElementUpdate extends CatalogElement
{
    /**
     * @param array $arFields - массив полей элемента
     * @return void - void при успехе
     * @throws LoaderException
     * Функция обновляет подраздел в каталоге в разделе АВТОМОБИЛИ
     */
    public static function execute(array &$arFields): void
    {
        if ((int)$arFields["IBLOCK_ID"] !== self::BRAND_IBLOCK_ID)
        {
            return;
        }
        Loader::includeModule("iblock");

        $elementId = $arFields["ID"];
        $newName = $arFields["NAME"];
        $active = $arFields["ACTIVE"];
        $code = "linked_" . $elementId;

        $res = CIBlockSection::GetList([], [
            "IBLOCK_ID" => self::CATALOG_IBLOCK_ID,
            "CODE" => $code
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