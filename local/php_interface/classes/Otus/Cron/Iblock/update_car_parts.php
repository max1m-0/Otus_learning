<?php

$_SERVER["DOCUMENT_ROOT"] = "/home/c/cg96022/Manage_Sites/public_html";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('BX_NO_ACCELERATOR_RESET', true);
define('CHK_EVENT', true);
define('BX_WITH_ON_AFTER_EPILOG', true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);

if (php_sapi_name() !== 'cli') die();

use Bitrix\Main\Loader;

Loader::includeModule("iblock");

const CATALOG_IBLOCK_ID = 79;

$elements = [];
$res = CIBlockElement::GetList([], ["IBLOCK_ID" => CATALOG_IBLOCK_ID, "ACTIVE" => "Y"], false, false, ["ID"]);
while ($item = $res->Fetch()) {
    $elements[] = $item["ID"];
}

$total = count($elements);
if ($total === 0) {
    echo "Нет элементов для обновления.";
    return;
}

$url = "https://www.random.org/integers/?num={$total}&min=0&max=4&col=1&base=10&format=plain&rnd=new";

$context = stream_context_create([
                                     'http' => [
                                         'method' => 'GET',
                                         'timeout' => 5,
                                         'header' => "User-Agent: PHP\r\n"
                                     ]
                                 ]);

$response = @file_get_contents($url, false, $context);
if ($response === false) {
    echo "Ошибка запроса к random.org";
    return;
}

$randomValues = preg_split('/\s+/', trim($response));

Loader::includeModule("catalog");
foreach ($elements as $index => $elementId) {
    $stock = (int)$randomValues[$index];
    \Bitrix\Catalog\Model\Product::update($elementId, ['QUANTITY' => $stock]);
    echo "Элемент #{$elementId} → наличие: {$stock}<br>";
}
