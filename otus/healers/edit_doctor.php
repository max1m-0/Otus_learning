<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if (!CModule::IncludeModule("iblock")) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
    exit;
}

$propertyCodes = [];
$res = CIBlock::GetProperties(16, [], []);
while ($prop = $res->Fetch()) {
    $propertyCodes[] = $prop["ID"];
}

$allProperties = array_merge(["NAME"], $propertyCodes);
$APPLICATION->IncludeComponent(
    "bitrix:iblock.element.add.form",
    "",
    [
        "SEF_MODE" => "N",
        "IBLOCK_ID" => "16",
        "PROPERTY_CODES" => $allProperties,
        "PROPERTY_CODES_REQUIRED" => [71],
        "LIST_URL" => "/otus/healers/index.php",
        "MAX_USER_ENTRIES" => "100000",
        "MAX_LEVELS" => "1",
        "LEVEL_LAST" => "Y",
        "USER_MESSAGE_EDIT" => "Врач успешно обновлён",
        "USER_MESSAGE_ADD" => "Врач успешно добавлен",
        "DEFAULT_INPUT_SIZE" => "30",
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>
