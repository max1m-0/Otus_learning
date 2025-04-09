<?php

namespace Otus\UserTypes;

use Bitrix\Main\Loader;
use CIBlockElement;
use CJSCore;

class ListIblock
{
    public static function GetUserTypeDescription(): array
    {
        return [
            "CLASS_NAME" => __CLASS__,
            "PROPERTY_TYPE" => "E",
            "USER_TYPE" => "custom_list",
            "DESCRIPTION" => "Процедуры врача (запись)",
            "GetPropertyFieldHtml" => [__CLASS__, 'GetPropertyFieldHtml'],
            "GetPublicViewHTML" => [__CLASS__, 'GetPublicViewHTML'],
        ];
    }

    public static function GetPublicViewHTML($arProperty, $arValue, $strHTMLControlName)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $healerId = (int)$arValue['ELEMENT_ID'];
        $procedureList = [];

        // Получаем все значения свойства PROCEDURE_ID у врача
        $procedureIds = [];
        $dbProps = \CIBlockElement::GetProperty(
            $arProperty['IBLOCK_ID'],
            $healerId,
            [],
            ['CODE' => 'PROCEDURE_ID']
        );

        while ($prop = $dbProps->Fetch()) {
            if (!empty($prop['VALUE'])) {
                $procedureIds[] = (int)$prop['VALUE'];
            }
        }

        if (empty($procedureIds)) {
            return '<div>Нет назначенных процедур</div>';
        }

        // Получаем сами процедуры
        $res = \CIBlockElement::GetList([], ['ID' => $procedureIds], false, false, ['ID', 'NAME']);
        while ($procedure = $res->Fetch()) {
            $procedureList[] = '<div style="margin-bottom: 10px;">
            <button onclick="showBookingPopup(' . (int)$procedure['ID'] . ')">' . htmlspecialcharsbx($procedure['NAME']) . '</button>
        </div>';
        }

        // Подключаем JS только один раз
        static $scriptIncluded = false;
        if (!$scriptIncluded) {
            echo '<script>
            function showBookingPopup(procedureId) {
                const popup = BX.PopupWindowManager.create("booking_popup", null, {
                    content: `
                    <form id="booking_form">
                        <label>ФИО пациента:</label><br>
                        <input type="text" name="FULL_NAME" required><br><br>
                        <label>Дата и время:</label><br>
                        <input type="datetime-local" name="RESERVATION_TIME" required><br><br>
                        <input type="hidden" name="PROCEDURE_ID" value="` + procedureId + `">
                        <button type="button" onclick="submitBooking()">Забронировать</button>
                    </form>
                    `,
                    closeIcon: true,
                    titleBar: "Бронирование процедуры",
                    width: 400,
                    autoHide: true,
                    buttons: []
                });
                popup.show();
            }

            function submitBooking() {
                const form = document.getElementById("booking_form");
                const formData = new FormData(form);

                BX.ajax({
                    url: "/local/ajax/booking_ajax.php",
                    method: "POST",
                    dataType: "json",
                    data: Object.fromEntries(formData),
                    onsuccess: function (response) {
                        if (response.status === "success") {
                            BX.PopupWindowManager.getCurrentPopup().close();
                            window.location.href = "/services/lists/74/view/0/?list_section_id=";
                        } else {
                            alert("Ошибка: " + response.message);
                        }
                    },
                    onfailure: function () {
                        alert("Ошибка при отправке.");
                    }
                });
            }
        </script>';
            $scriptIncluded = true;
        }

        return implode('', $procedureList);
    }

    public static function GetPropertyFieldHtml($arProperty, $arValue, $strHTMLControlName)
    {
        return '<input type="text" name="' . $strHTMLControlName["VALUE"] . '" value="' . htmlspecialcharsbx($arValue["VALUE"]) . '">';
    }
}
