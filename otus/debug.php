<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
if (CModule::IncludeModule('crm')) {
    $APPLICATION->IncludeComponent(
        'otus.homework:otus.grid',
        '',
        array(
            'DEAL_ID' => $arResult['dealId']
        ),
        false
    );
}
/** @var \CMain $APPLICATION */
print_r(0/0);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
