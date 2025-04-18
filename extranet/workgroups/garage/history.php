<?php
define("PUBLIC_AJAX_MODE", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
if ($_GET['IFRAME'] !== 'Y') {
    LocalRedirect('/extranet/workgroups/garage/');
    exit;
}
Loader::includeModule("iblock");
CJSCore::Init(['sidepanel']);

$carId = (int)$_GET['car_id'];

$APPLICATION->ShowHead();
$APPLICATION->SetTitle("История автомобиля");

?>

<?php
$APPLICATION->IncludeComponent("otus:car.history", "", [
    "CAR_ID" => $carId,
]);
?>

