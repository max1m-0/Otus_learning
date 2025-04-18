<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
if (!$arResult['NO_CARS'])
{
    $APPLICATION->IncludeComponent(
        'bitrix:main.ui.grid',
        '',
        [
            'GRID_ID' => 'car_deals_' . $arResult['CAR_ID'],
            'COLUMNS' => $arResult['COLUMNS'],
            'ROWS' => $arResult['ROWS'],
            'SHOW_ROW_CHECKBOXES' => false,
            'NAV_OBJECT' => null,
            'AJAX_MODE' => 'N',
            'SHOW_CHECK_ALL_CHECKBOXES' => false,
            'SHOW_ROW_ACTIONS_MENU' => false,
            'SHOW_GRID_SETTINGS_MENU' => false,
            'SHOW_NAVIGATION_PANEL' => false,
            'SHOW_PAGINATION' => false,
            'SHOW_TOTAL_COUNTER' => false,
            'SHOW_PAGESIZE' => false,
            'SHOW_ACTION_PANEL' => false,
            'ALLOW_COLUMNS_SORT' => false,
        ]
    );
}else{
    echo(GetMessage("NO_CARS"));
}
?>

