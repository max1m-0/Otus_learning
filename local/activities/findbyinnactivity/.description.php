<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Localization\Loc;

$arActivityDescription = [
	'NAME' => Loc::getMessage('BPAA_DESCR_NAME'),
	'DESCRIPTION' => Loc::getMessage('BPAA_DESCR_DESCR'),
	'TYPE' => 'activity',
	'CLASS' => 'FindByInnActivity',
	'CATEGORY' => [
		'ID' => 'other',
	],
	'RETURN' => [
		'COMPANY_NAME' => [
			'NAME' => Loc::getMessage('BPAA_COMP_ID'),
			'TYPE' => 'int',
		],
	],
];
