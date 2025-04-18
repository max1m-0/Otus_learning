<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Crm\Service\Container;
use Bitrix\Crm\StatusTable;

Loader::includeModule('crm');
Loader::includeModule('iblock');

$carId = (int)$_GET['car_id'];
$arResult['CAR_ID'] = $carId;

$factory = Container::getInstance()->getFactory(CCrmOwnerType::Deal);


$deals = $factory->getItems([
                                'filter' => ['=UF_CRM_1744824780' => $carId],
                                'select' => ['ID', 'TITLE', 'STAGE_ID', 'ASSIGNED_BY_ID', 'DATE_CREATE', 'UF_CRM_1744824780'],
                            ]);
if (empty($deals))
{
    $arResult['NO_CARS'] = true;
    $this->IncludeComponentTemplate();
    return;
}
$arResult['ROWS'] = [];
    $stageNames = [];
    $statusList = StatusTable::getList([
                                           'filter' => ['=ENTITY_ID' => 'DEAL_STAGE_1'],
                                           'select' => ['STATUS_ID', 'NAME']
                                       ]);
    while ($status = $statusList->fetch()) {
        $stageNames[$status['STATUS_ID']] = $status['NAME'];
    }
foreach ($deals as $deal) {
    $carName = '';
    $carElementId = $deal->get('UF_CRM_1744824780');
    if ($carElementId) {
        $carRes = CIBlockElement::GetByID($carElementId);
        if ($carData = $carRes->GetNext()) {
            $carName = $carData['NAME'];
        }
    }

    $userName = '';
    $userId = $deal->getAssignedById();
    if ($userId) {
        $rsUser = CUser::GetByID($userId);
        if ($arUser = $rsUser->Fetch()) {
            $userName = $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
        }
    }

    $productRows = [];
    $productResult = \CCrmProductRow::GetList(
        [],
        ['OWNER_ID' => $deal->getId()],
        false,
        false,
        ['PRODUCT_NAME']
    );
    while ($product = $productResult->Fetch()) {
        $productRows[] = $product['PRODUCT_NAME'];
    }

    $arResult['ROWS'][] = [
        'id' => $deal->getId(),
        'columns' => [
            'CAR' => $carName,
            'STAGE_ID' => $stageNames[$deal->getStageId()],
            'ASSIGNED_BY' => $userName,
            'DATE_CREATE' => $deal->getDateCreate()->toString(),
            'CAR_PARTS' => implode("<br>", $productRows),
        ],
    ];
}

$arResult['COLUMNS'] = [
    ['id' => 'CAR', 'name' => GetMessage("CAR"), 'default' => true],
    ['id' => 'STAGE_ID', 'name' => GetMessage("STAGE"), 'default' => true],
    ['id' => 'ASSIGNED_BY', 'name' => GetMessage("ASSIGNED_BY"), 'default' => true],
    ['id' => 'DATE_CREATE', 'name' => GetMessage("DATE_CREATE"), 'default' => true],
    ['id' => 'CAR_PARTS', 'name' => GetMessage("CAR_PARTS"), 'default' => true],
];

$this->IncludeComponentTemplate();
