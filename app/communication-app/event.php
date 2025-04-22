<?php
use Bitrix\Main\Loader;

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$data = json_decode(file_get_contents("php://input"), true);
file_put_contents(__DIR__."/event_log.txt", print_r($data, true), FILE_APPEND);

if (empty($data['event'])) {
    http_response_code(400);
    exit("No event");
}

switch ($data['event']) {
    case 'OnImMessageAdd':
        $userId = intval($data['data']['FIELDS']['AUTHOR_ID']);
        break;
    case 'OnCallEnd':
        $userId = intval($data['data']['USER_ID']);
        break;
    default:
        http_response_code(400);
        exit("Unknown event");
}

Loader::includeModule('crm');
Loader::includeModule('im');

$res = CCrmContact::GetList(
    ['ID'=>'ASC'],
    ['ASSIGNED_BY_ID' => $userId],
    false,
    ['nTopCount'=>1],
    ['ID']
);

if ($contact = $res->Fetch()) {
    $today = date("c");
    $fields = ["UF_CRM_1744986102" => $today];

    $contactObj = new CCrmContact();
    $contactObj->Update($contact['ID'], $fields);
}

http_response_code(200);
echo json_encode(["result" => "ok"]);
