<?php
require_once (__DIR__.'/crest.php');


if (!empty($_REQUEST['event']) && !empty($_REQUEST['data'])) {
    $event = $_REQUEST['event'];
    $data = $_REQUEST['data'];

    if ($event === 'ONBEFOREMESSAGESADD') {
        if (!empty($data['USER_ID'])) {
            $contactId = findContactByUserId($data['FROM_USER_ID']);
            if ($contactId) {
                updateContactField($contactId, "Новое сообщение в чате");
            }
        }
    } elseif ($event === 'ONCALLEND') {
        if (!empty($data['CALL_ID']) && !empty($data['PORTAL_USER_ID'])) {
            $contactId = findContactByUserId($data['PORTAL_USER_ID']);
            if ($contactId) {
                updateContactField($contactId, "Звонок завершён");
            }
        }
    }
}

function findContactByUserId($userId) {
    $result = CRest::call(
        'crm.contact.list',
        [
            'filter' => ['ASSIGNED_BY_ID' => $userId],
            'select' => ['ID']
        ]
    );

    if (!empty($result['result'][0]['ID'])) {
        return $result['result'][0]['ID'];
    }

    return false;
}

function updateContactField($contactId, $value) {
    $updateResult = CRest::call(
        'crm.contact.update',
        [
            'id' => $contactId,
            'fields' => [
                'UF_CRM_LAST_COMM_RESULT' => $value
            ]
        ]
    );

    if (isset($updateResult['error'])) {
        $path = __DIR__.'/logs/'.date("Y-m-d/H");
        if (!file_exists($path))
        {
            @mkdir($path, 0775, true);
        }
        file_put_contents( $path. '/handler_errors.txt', var_export($updateResult, true), FILE_APPEND);
    }
}

?>
