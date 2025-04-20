<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Otus\Models\HospitalClientsTable;
$APPLICATION->SetTitle(GetMessage("TITLE"));

$hospitalClients = HospitalClientsTable::query()
    ->setSelect(
        [
            'id',
            'first_name',
            'age',
            "HEALERS" => 'HEALERS.ELEMENT',
            "HEALERS_NAME" => 'HEALERS.NAME',
            "HEALERS_SURNAME" => 'HEALERS.SURNAME',
            "HEALERS_SECOND_NAME" => 'HEALERS.SECOND_NAME',
            "PROCEDURES" => 'PROCEDURES.ELEMENT',
        ],
    )
    ->fetchCollection();
$res = CIBlockElement::GetList(
    ["SORT" => "ASC"],
    [
        "IBLOCK_ID" => PROCEDURE_IBLOCK_ID
    ],
    false,
    false,
    ["ID", "NAME"]
);
while ($element = $res->Fetch()) {
    $procedureNames[$element["ID"]] = $element["NAME"];
}
if ($hospitalClients)
{
    $gridRows = [];
    foreach ($hospitalClients as $client)
    {
        $gridRows[] = [
            'id' => $client->get('id'),
            'columns' => [
                'id' => $client->get('id'),
                'first_name' => $client->get('first_name'),
                'age' => $client->get('age'),
                'healers' => $client->get('HEALERS')->get("NAME") . ' ' . $client->get('HEALERS')->get("SURNAME") . ' ' . $client->get('HEALERS')->get("SECOND_NAME"),
                'procedure' =>$procedureNames[$client->get('PROCEDURES')->get("IBLOCK_ELEMENT_ID")] ?? '-',
            ],
        ];
    }

    $gridColumns = [
        ['id' => 'id', 'name' => 'ID', 'default' => true],
        ['id' => 'first_name', 'name' => GetMessage("COLUMN_NAME"), 'default' => true],
        ['id' => 'age', 'name' => GetMessage("COLUMN_AGE"), 'default' => true],
        ['id' => 'healers', 'name' => GetMessage("COLUMN_HEALER"), 'default' => true],
        ['id' => 'procedure', 'name' => GetMessage("COLUMN_PROCEDURE"), 'default' => true],
    ];
    $APPLICATION->IncludeComponent(
        'bitrix:main.ui.grid',
        '',
        [
            'GRID_ID' => 'hospital_clients_grid',
            'COLUMNS' => $gridColumns,
            'ROWS' => $gridRows,
            'SHOW_ROW_CHECKBOXES' => false,
            'SHOW_ROW_ACTIONS_MENU' => true,
            'SHOW_GRID_SETTINGS_MENU' => true,
            'SHOW_PAGINATION' => true,
            'SHOW_NAVIGATION_PANEL' => false,
            'ALLOW_COLUMNS_SORT' => true,
        ],
    );
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");