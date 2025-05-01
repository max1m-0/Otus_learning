<?php
require_once (__DIR__.'/crest.php');

$result = CRest::installApp();

    if($result['install'] == true){
        CRest::call('event.bind', [
            'event' => 'ONCRMACTIVITYADD',
            'handler' => 'https://cg96022.tw1.ru/app/communication-app/handler.php'
        ]);
    }
