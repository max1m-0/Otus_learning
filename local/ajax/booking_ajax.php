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
$reservationTime=date("d.m.Y H:i:s",strtotime($reservationTime));
$iblockId = 74;
$filter = [
    "IBLOCK_ID" => $iblockId,
    "PROPERTY_VALUES" => [
        "RESERVATION_TIME" => $reservationTime,
        "PROCEDURE_ID" => $procedureId,
    ],
    "ACTIVE" => "Y",
];
$reservationExists = \CIBlockElement::GetList(
    [],
    $filter,
    false,
    false,
    ["ID"]
)->Fetch();
if ($reservationExists) {
    echo json_encode(["status" => "error", "message" => "На это время и процедуру уже существует бронь"]);
    die();
}
$el = new CIBlockElement;
$fields = [
    "IBLOCK_ID" => $iblockId,
    "NAME" => "Бронь на процедуру #" . $procedureId,
    "ACTIVE" => "Y",
    "PROPERTY_VALUES" => [
        "FULL_NAME" => $fullName,
        "RESERVATION_TIME" => $reservationTime,
        "PROCEDURE_ID" => $procedureId,
    ]
];

if ($el->Add($fields)) {
    echo json_encode(["status" => "success", "message" => "Бронирование успешно!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Ошибка при записи"]);
}
die();
