<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
        'GRID_ID' => 'crm_tab_custom_grid',
        'COLUMNS' => $arResult['COLUMNS'],
        'ROWS' => $arResult['ROWS'],
        'AJAX_MODE' => 'Y',
        'SHOW_ROW_CHECKBOXES' => false,
        'SHOW_GRID_SETTINGS_MENU' => false,
        'SHOW_NAVIGATION_PANEL' => false,
        'SHOW_PAGINATION' => false,
        'SHOW_TOTAL_COUNTER' => false,
        'SHOW_PAGESIZE' => false,
        'SHOW_ACTION_PANEL' => false,
        'ALLOW_COLUMNS_SORT' => false,
        'ALLOW_COLUMNS_RESIZE' => true,
        'ALLOW_HORIZONTAL_SCROLL' => true,
        'ALLOW_SORT' => false,
        'ALLOW_PIN_HEADER' => true,
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_HISTORY' => 'N',
    ]
);
