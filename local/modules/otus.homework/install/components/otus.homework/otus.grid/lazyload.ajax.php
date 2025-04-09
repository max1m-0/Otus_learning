<?php

declare(strict_types=1);

use Bitrix\Crm\Service\Container;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;

define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NO_AGENT_CHECK', true);
define('PUBLIC_AJAX_MODE', true);
define('DisableEventsCheck', true);

$siteID = isset($_REQUEST['site']) ? mb_substr(preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['site']), 0, 2) : '';
if ('' !== $siteID) {
    define('SITE_ID', $siteID);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!CModule::IncludeModule('crm') || !CCrmSecurityHelper::IsAuthorized() || !check_bitrix_sessid()) {
    die();
}

$id = (int)Application::getInstance()->getContext()->getRequest()->get('ID');
$typeId = (int)Application::getInstance()->getContext()->getRequest()->get('TYPE_ID');

$APPLICATION->IncludeComponent(
    'otus.homework:otus.grid',
    '',
    [
        'typeId' => $typeId,
        'id' => $id
    ],
    true,
);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
die();
