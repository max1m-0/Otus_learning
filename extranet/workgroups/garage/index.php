<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

Loader::includeModule("iblock");
global $USER;

$APPLICATION->SetTitle(GetMessage("ABOUT_TITLE"));

$APPLICATION->IncludeComponent("otus:client.garage", "", []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
