<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if (!CModule::IncludeModule("iblock")) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
    exit;
}

$propertyCodes = [];
$res = CIBlock::GetProperties(17, [], []);
while ($prop = $res->Fetch()) {
    $propertyCodes[] = $prop["ID"];
}

$allProperties = array_merge(["NAME"], $propertyCodes);
$APPLICATION->IncludeComponent(
    "bitrix:iblock.element.add.form",
    "",
    [
        "SEF_MODE" => "N",
        "IBLOCK_ID" => "17",
        "PROPERTY_CODES" => $allProperties,
        "PROPERTY_CODES_REQUIRED" => ["NAME"],
        "LIST_URL" => "/otus/healers/index.php",
        "MAX_USER_ENTRIES" => "100000",
        "MAX_LEVELS" => "1",
        "LEVEL_LAST" => "Y",
        "USER_MESSAGE_EDIT" => "Процедура успешно обновлёна",
        "USER_MESSAGE_ADD" => "Процедура успешно добавлена",
        "DEFAULT_INPUT_SIZE" => "30",
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>
