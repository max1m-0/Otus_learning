<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if (!CModule::IncludeModule("iblock")) {
    echo "Модуль инфоблоков не подключен";
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
    exit;
}

use Bitrix\Iblock\ElementTable;
use Otus\Models\Lists\HealersPropertyValuesTable;

$doctorId = intval($_GET["doctor_id"]);
if ($doctorId <= 0) {
    echo "Врач не указан";
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
    exit;
}

$APPLICATION->SetTitle("Процедуры врача");

$procedureIds = [];
$res = CIBlockElement::GetProperty(16, $doctorId, [], ["CODE" => "PROCEDURE_ID"]);
$healer = HealersPropertyValuesTable::getList(
    [
        'select' =>
            [
                "ID" => "IBLOCK_ELEMENT_ID",
                "*",
            ],
    ],
)->fetchObject();
while ($prop = $res->Fetch()) {
    if (!empty($prop['VALUE'])) {
        $procedureIds[] = $prop['VALUE'];
    }
}

if (empty($procedureIds)) {
    echo "<p>У врача нет привязанных процедур.</p>";
} else {
    $procedures = ElementTable::getList([
                                            'filter' => ['ID' => $procedureIds],
                                            'select' => ['ID', 'NAME'],
                                        ])->fetchAll();

    echo "<h2>Процедуры врача ".$healer->getName()."</h2><ul>";
    foreach ($procedures as $procedure) {
        echo "<li>".  htmlspecialchars($procedure['NAME']) . "
            </a>
        </li>";
    }
    echo "</ul>";
}

echo "<a href='index.php' style='display: inline-block; padding: 10px; background: green; color: white; text-decoration: none; margin-bottom: 20px;'>Назад</a>";
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>
