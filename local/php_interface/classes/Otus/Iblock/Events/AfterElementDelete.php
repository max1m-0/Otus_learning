<?php

namespace Otus\Iblock\Events;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CIBlockElement;
use CIBlockSection;

class AfterElementDelete extends CatalogElement
{
    /**
     * @param array $arFields - массив полей элемента
     * @return void - void при успехе
     * Функция удаляет подраздел в каталоге в разделе АВТОМОБИЛИ
     * @throws LoaderException
     */
    public static function execute(&$arFields): void
    {
        if ((int)$arFields["IBLOCK_ID"] !== self::BRAND_IBLOCK_ID)
        {
            return;
        }
        Loader::includeModule("iblock");
        $elementId = $arFields["ID"];
        $code = "linked_" . $elementId;
        $resSec = CIBlockSection::GetList(
            [], [
            "IBLOCK_ID" => self::CATALOG_IBLOCK_ID,
            "CODE" => $code,
        ],
        );
        if ($section = $resSec->Fetch())
        {
            CIBlockSection::Delete($section["ID"]);
        }
    }

}