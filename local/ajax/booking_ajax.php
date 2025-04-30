<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use CIBlockElement;

Loader::includeModule('iblock');

$request = Application::getInstance()->getContext()->getRequest();
$fullName = $request->getPost("FULL_NAME");
$reservationTime = $request->getPost("RESERVATION_TIME");
$procedureId = (int)$request->getPost("PROCEDURE_ID");

if (!$fullName || !$reservationTime || !$procedureId) {
    echo json_encode(["status" => "error", "message" => "Заполните все поля"]);
    die();
}

$timestamp = strtotime($reservationTime);

$timeFrom = date("Y-m-d H:i:s", $timestamp - 15 * 60);
$timeTo = date("Y-m-d H:i:s", $timestamp + 15 * 60);

$iblockId = 74;
$filter = [
    "IBLOCK_ID" => $iblockId,
    "ACTIVE" => "Y",
    ">=PROPERTY_RESERVATION_TIME" => $timeFrom,
    "<=PROPERTY_RESERVATION_TIME" => $timeTo,
    "PROPERTY_PROCEDURE_ID" => $procedureId,
];

$reservationExists = CIBlockElement::GetList(
    [],
    $filter,
    false,
    false,
    ["ID"]
)->Fetch();

if ($reservationExists) {
    echo json_encode(["status" => "error", "message" => "На это время и процедуру уже существует бронь в пределах 15 минут"]);
    die();
}

$el = new CIBlockElement;
$fields = [
    "IBLOCK_ID" => $iblockId,
    "NAME" => "Бронь на процедуру #" . $procedureId,
    "ACTIVE" => "Y",
    "PROPERTY_VALUES" => [
        "FULL_NAME" => $fullName,
        "RESERVATION_TIME" => date("d.m.Y H:i:s", $timestamp),
        "PROCEDURE_ID" => $procedureId,
    ]
];

if ($el->Add($fields)) {
    echo json_encode(["status" => "success", "message" => "Бронирование успешно!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Ошибка при записи"]);
}
die();
