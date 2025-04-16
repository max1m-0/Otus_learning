<?php

namespace Otus\Iblock\Events;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

class AfterElementAdd extends CatalogElement
{

    /**
     * @param $arFields
     * @return void
     * @throws LoaderException
     * Функция создает подраздел в каталоге в разделе АВТОМОБИЛИ
     */
    public static function execute(&$arFields): void
    {
        if ((int)$arFields["IBLOCK_ID"] !== self::BRAND_IBLOCK_ID)
        {
            return;
        }
        file_put_contents($_SERVER['DOCUMENT_ROOT']."/local/iblock.txt",print_r($arFields,true),FILE_APPEND);

        Loader::includeModule("iblock");

        $elementId = $arFields["ID"];
        $elementName = $arFields["NAME"];
        $active = $arFields['ACTIVE'];

        (new \CIBlockSection)->Add([
                                "IBLOCK_ID" => self::CATALOG_IBLOCK_ID,
                                "IBLOCK_SECTION_ID" => self::CATALOG_CARS_SECTION_ID,
                                "NAME" => $elementName,
                                "ACTIVE" => $active,
                                "CODE" => "linked_" . $elementId
                            ]);
    }
}