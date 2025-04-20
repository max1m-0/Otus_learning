<?php
$_SERVER["DOCUMENT_ROOT"] = "/home/c/cg96022/Manage_Sites/public_html";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_NO_ACCELERATOR_RESET", true);
define("CHK_EVENT", true);
define("BX_WITH_ON_AFTER_EPILOG", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);

if (php_sapi_name() !== 'cli')
{
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Catalog\ProductTable;
use Bitrix\Catalog\Model\Product;

Loader::includeModule("iblock");
Loader::includeModule("catalog");
Loader::includeModule("bizproc");

const CAR_PARTS_CATALOG_IBLOCK_ID = 79;
const TIMEOUT = 5;
const QUANTITY = 10;
const HEADER = "User-Agent: PHP\r\n";

$elements = [];
$res = CIBlockElement::GetList(
    [],
    [
        "IBLOCK_ID" => CAR_PARTS_CATALOG_IBLOCK_ID,
        "ACTIVE" => "Y",
    ],
    false,
    false,
    ["ID"],
);

while ($item = $res->Fetch())
{
    $elements[] = $item["ID"];
}

$total = count($elements);
if ($total === 0)
{
    return;
}
$url = "https://www.random.org/integers/?" . http_build_query(
        [
            'num' => $total,
            'min' => 0,
            'max' => 4,
            'col' => 1,
            'base' => 10,
            'format' => 'plain',
            'rnd' => 'new',
        ],
    );

$context = stream_context_create(
    [
        'http' => [
            'method' => 'GET',
            'timeout' => TIMEOUT,
            'header' => HEADER,
        ],
    ],
);

$response = @file_get_contents($url, false, $context);
if ($response === false)
{
    return;
}

$randomValues = preg_split('/\s+/', trim($response));

foreach ($elements as $index => $elementId)
{
    $stock = (int)$randomValues[$index];
    if ($stock === 0)
    {
        $stock = 10;
    }
    Product::update($elementId, ['QUANTITY' => $stock]);

}
