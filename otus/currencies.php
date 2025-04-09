<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Otus\Models\Lists\HealersPropertyValuesTable;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->IncludeComponent(
    "otus.currency",
    "",
    [
        "DEFAULT_CURRENCY" => "USD",
    ]
);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
