<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

global $USER;

if (!$USER || !$USER->IsAuthorized())
{
    $this->arResult["ERROR"] = "Пользователь не авторизован.";
    $this->includeComponentTemplate();

    return;
}

if (!Loader::includeModule("iblock"))
{
    $this->arResult["ERROR"] = "Модуль инфоблоков не подключен.";
    $this->includeComponentTemplate();

    return;
}

$clientId = $USER->GetID();

$cars = [];
$res = \CIBlockElement::GetList(
    ["NAME" => "ASC"],
    [
        "IBLOCK_ID" => 14,
        "ACTIVE" => "Y",
        "PROPERTY_CAR_OWNER" => $clientId,
    ],
    false,
    false,
    [
        "ID",
        "NAME",
        "PROPERTY_CAR_MODEL",
        "PROPERTY_MORE_PHOTO",
        "PROPERTY_CAR_YEAR",
        "PROPERTY_CAR_COLOR",
        "PROPERTY_CAR_KILOMETERS",
        "PROPERTY_CAR_BRAND",
    ],
);

while ($car = $res->Fetch())
{
    $photoPaths = [];

    if (!empty($car["PROPERTY_MORE_PHOTO_VALUE"]))
    {
        $photoIds = is_array($car["PROPERTY_MORE_PHOTO_VALUE"])
            ? $car["PROPERTY_MORE_PHOTO_VALUE"]
            : [$car["PROPERTY_MORE_PHOTO_VALUE"]];

        foreach ($photoIds as $photoId)
        {
            $photoPath = CFile::GetPath($photoId);
            if ($photoPath)
            {
                $photoPaths[] = $photoPath;
            }
        }
    }

    $brandName = '';
    if (!empty($car["PROPERTY_CAR_BRAND_VALUE"])) {
        $brandRes = CIBlockElement::GetByID($car["PROPERTY_CAR_BRAND_VALUE"]);
        if ($brandItem = $brandRes->GetNext()) {
            $brandName = $brandItem["NAME"];
        }
    }
    $modelName = '';
    if (!empty($car["PROPERTY_CAR_MODEL_VALUE"])) {
        $modelRes = CIBlockElement::GetByID($car["PROPERTY_CAR_MODEL_VALUE"]);
        if ($brandItem = $modelRes->GetNext()) {
            $modelName = $brandItem["NAME"];
        }
    }
    $cars[] = [
        "ID" => $car["ID"],
        "NAME" => $car["NAME"],
        "MODEL" => $modelName,
        "BRAND" => $brandName,
        "YEAR" => $car["PROPERTY_CAR_YEAR_VALUE"],
        "COLOR" => $car["PROPERTY_CAR_COLOR_VALUE"],
        "KILOMETERS" => $car["PROPERTY_CAR_KILOMETERS_VALUE"],
        "PHOTOS" => $photoPaths,
    ];
}

$this->arResult["CARS"] = $cars;
$this->includeComponentTemplate();
