<?php

namespace Otus\Iblock\Events;

/**
 * Абстрактный класс для элементов каталога
 */
abstract class CatalogElement
{
    /**
     * ID инфоблока с марками авто
     */
    public const BRAND_IBLOCK_ID = 77;
    /**
     * ID инфоблока каталог
     */
    public const CATALOG_IBLOCK_ID = 14;
    /**
     * ID секции каталога АВТОМОБИЛИ
     */
    public const CATALOG_CARS_SECTION_ID = 17;

    /**
     * @param $arFields
     * @return void
     */
    abstract public static function execute(&$arFields) :void;
}