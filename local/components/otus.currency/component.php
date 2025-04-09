<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Currency\CurrencyTable;
use Bitrix\Main\Loader;

if (!Loader::includeModule("currency")) {
    ShowError("Модуль currency не подключен");
    return;
}

// Получаем список валют
$currencyList = [];
$result = CurrencyTable::getList([
                                     'select' => ['CURRENCY', 'BASE', 'SORT'],
                                     'order' => ['SORT' => 'ASC']
                                 ]);

while ($currency = $result->fetch()) {
    $currencyList[$currency['CURRENCY']] = $currency['CURRENCY'];
}

$this->arResult['CURRENCIES'] = $currencyList;

$defaultCurrency = $this->arParams['DEFAULT_CURRENCY'];

$currencyRate = CCurrencyRates::GetConvertFactor($defaultCurrency,$_GET['currency']);

$this->arResult['DEFAULT_CURRENCY'] = $defaultCurrency;
$this->arResult['CURRENCY_RATE'] = $currencyRate;

$this->includeComponentTemplate();