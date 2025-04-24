<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Otus\Models\Lists\HealersPropertyValuesTable;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$healers = HealersPropertyValuesTable::getList(
    [
        'select' =>
            [
                "ID" => "IBLOCK_ELEMENT_ID",
                "*",
            ],
    ],
)->fetchCollection();

echo "<h2>Список врачей</h2>";

echo "<ul>";
foreach ($healers as $healer)
{
    echo "<li>
| <a href='edit_doctor.php?edit=Y&CODE={$healer->get('IBLOCK_ELEMENT_ID')}'>Редактировать</a> |
<a href='get_procedure.php?doctor_id={$healer->get('IBLOCK_ELEMENT_ID')}'>".htmlspecialchars($healer->get('NAME'))."</a>
</li>";
}
echo "</ul>";
echo "<a href='edit_doctor.php' style='display: inline-block; padding: 10px; background: green; color: white; text-decoration: none; margin-bottom: 20px;'>Добавить врача</a>";

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
