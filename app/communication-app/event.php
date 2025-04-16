<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$data = json_decode(file_get_contents("php://input"), true);
file_put_contents(__DIR__."/event_log.txt", print_r($data, true), FILE_APPEND);

if (!isset($data['event'])) {
    http_response_code(400);
    exit("No event");
}

$event = $data['event'];
$today = date("c");

if ($event === 'OnImMessageAdd') {
    $userId = $data['data']['FIELDS']['AUTHOR_ID'];
} elseif ($event === 'OnCallEnd') {
    $userId = $data['data']['USER_ID'];
} else {
    exit("Unknown event");
}

// Найти контакт по ответственному
$res = \CCrmContact::GetList([], ["ASSIGNED_BY_ID" => $userId], false, false, ["ID"]);
if ($contact = $res->Fetch()) {
    $arr = [
        "UF_LAST_COMM" => $today
    ];
    (new CCrmContact)->Update($contact['ID'], $arr);
}
