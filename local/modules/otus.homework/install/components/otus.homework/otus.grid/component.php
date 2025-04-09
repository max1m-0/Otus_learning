<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule('iblock')) return;

$arFilter = ['CODE' => 'test_list'];
$rsIBlock = CIBlock::GetList([], $arFilter);

if ($arIBlock = $rsIBlock->Fetch()) {
    $iblockId = $arIBlock['ID'];
    $this->arResult['iblockId'] = $iblockId;
} else {
    ShowError('Инфоблок с таким CODE не найден.');
    return;
}

// Получаем список элементов
$arResult['ROWS'] = [];
$arResult['COLUMNS'] = [
    ['id' => 'ID', 'name' => 'ID', 'default' => true],
    ['id' => 'NAME', 'name' => 'Название', 'default' => true],
    ['id' => 'HTML_CODE', 'name' => 'HTML-код', 'default' => true],
];

$res = \CIBlockElement::GetList(
    ["ID" => "DESC"],
    [
        "IBLOCK_ID" => $iblockId,
        'ACTIVE' => 'Y',
        "PROPERTY_CONNECTED_TYPE_ID" => $arParams['typeId'],
        "PROPERTY_CONNECTED_ID" => $arParams['id'],
    ],
    false,
    false,
    ["ID", "NAME", "PROPERTY_HTML_CODE"]
);

while ($element = $res->Fetch()) {
    $arResult['ROWS'][] = [
        'id' => $element['ID'],
        'data' => $element,
        'columns' => [
            'ID' => $element['ID'],
            'NAME' => htmlspecialcharsbx($element['NAME']),
            'HTML_CODE' => htmlspecialcharsbx($element['PROPERTY_HTML_CODE_VALUE']["TEXT"]),
        ],
    ];
}

$this->IncludeComponentTemplate();
