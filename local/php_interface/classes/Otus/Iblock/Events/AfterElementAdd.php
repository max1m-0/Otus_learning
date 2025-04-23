<?php

namespace Otus\Iblock\Events;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

class AfterElementAdd extends CatalogElement
{

    /**
     * @param array $arFields - массив полей элемента
     * @return void - void при успехе
     * @throws LoaderException Функция создает подраздел в каталоге в разделе АВТОМОБИЛИ
     */
    public static function execute(array &$arFields): void
    {
        if ((int)$arFields["IBLOCK_ID"] !== self::BRAND_IBLOCK_ID)
        {
            return;
        }

        Loader::includeModule("iblock");

        $elementId = $arFields["ID"];
        $elementName = $arFields["NAME"];
        $active = $arFields['ACTIVE'];
        $code = "linked_" . $elementId;

        (new \CIBlockSection)->Add([
                                "IBLOCK_ID" => self::CATALOG_IBLOCK_ID,
                                "IBLOCK_SECTION_ID" => self::CATALOG_CARS_SECTION_ID,
                                "NAME" => $elementName,
                                "ACTIVE" => $active,
                                "CODE" => $code
                            ]);
    }
}