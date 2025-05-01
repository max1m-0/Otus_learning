<?php
require_once (__DIR__.'/crest.php');

if (!empty($_REQUEST['event']) && !empty($_REQUEST['data'])) {
    $event = $_REQUEST['event'];
    $data = $_REQUEST['data'];
    file_put_contents( 'handler_errors.txt', var_export([$data,$event], true), FILE_APPEND);

    if ($event === 'ONCRMACTIVITYADD') {
        $activityId = $data['FIELDS']['ID'];

        $activity = CRest::call('crm.activity.get', ['id' => $activityId]);

        if ($activity && isset($activity['result'])) {

            $activity = $activity['result'];

            if ($activity['OWNER_TYPE_ID'] == 3) {
                $type = $activity['PROVIDER_TYPE_ID'];

                if (in_array($type, ['CALL', 'IM', 'OPENLINE'])) {
                    $contactId = $activity['OWNER_ID'];
                    updateContactField($contactId, date('c'));
                }
            }

        }
    }
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
