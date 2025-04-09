<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class otus_homework extends CModule
{
    public $MODULE_ID = 'otus.homework';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('OTUS_VACATION_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('OTUS_VACATION_MODULE_DESC');

        $this->PARTNER_NAME = Loc::getMessage('OTUS_VACATION_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('OTUS_VACATION_PARTNER_URI');
    }

    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '20.00.00');
    }

    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot)
        {
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        }
        else
        {
            return dirname(__DIR__);
        }
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if ($this->isVersionD7())
        {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->installFiles();
            $this->InstallEvents();
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage('OTUS_VACATION_INSTALL_ERROR_VERSION'));
        }
    }

    public function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installFiles($arParams = [])
    {
        $component_path = $this->GetPath() . '/install/components';

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($component_path))
        {
            CopyDirFiles($component_path, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components', true, true);
        }
        else
        {
            throw new \Bitrix\Main\IO\InvalidPathException($component_path);
        }
    }

    public function InstallDB()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $arAccess = [
            1 => 'X',
            2 => 'R',
        ];
        $arFields = [
            'ACTIVE' => 'Y',
            'NAME' => 'Тестовый универсальный список',
            'CODE' => 'test_list',
            'IBLOCK_TYPE_ID' => 'lists',
            'SITE_ID' => 's1',
            'SORT' => '500',
            'GROUP_ID' => $arAccess, // Права доступа
            'INDEX_ELEMENT' => 'Y', // Индексировать элементы для модуля поиска
            'VERSION' => 2,
        ];
        $ib = new \CIBlock;
        $iblockId = $ib->Add($arFields);
        \Bitrix\Main\Config\Option::set($this->MODULE_ID, 'test_iblock_id', $iblockId);
        $el = new \CIBlockElement;
        $dbProperties = CIBlockProperty::GetList([], ["IBLOCK_ID" => $iblockId]);
        if ($dbProperties->SelectedRowsCount() <= 0)
        {
            $ibp = new CIBlockProperty;

            $arFields = [
                "NAME" => "[служебное] ID привязанного элемента",
                "ACTIVE" => "Y",
                "SORT" => 100, // Сортировка
                "CODE" => "CONNECTED_ID",
                "PROPERTY_TYPE" => "N", // Число
                "FILTRABLE" => "Y", // Выводить на странице списка элементов поле для фильтрации по этому свойству
                "IBLOCK_ID" => $iblockId,
            ];
            $propId = $ibp->Add($arFields);
            if ($propId > 0)
            {
                $arFields["ID"] = $propId;
                $arCommonProps[$arFields["CODE"]] = $arFields;
            }
            else
                echo "&mdash; Ошибка добавления свойства " . $arFields["NAME"] . "<br />";

            $arFields = [
                "NAME" => "[служебное] TYPE_ID привязанного элемента",
                "ACTIVE" => "Y",
                "SORT" => 200, // Сортировка
                "CODE" => "CONNECTED_TYPE_ID",
                "PROPERTY_TYPE" => "N", // Число
                "FILTRABLE" => "Y", // Выводить на странице списка элементов поле для фильтрации по этому свойству
                "IBLOCK_ID" => $iblockId,
            ];
            $propId = $ibp->Add($arFields);
            if ($propId > 0)
            {
                $arFields["ID"] = $propId;
                $arCommonProps[$arFields["CODE"]] = $arFields;
            }
            else
                echo "&mdash; Ошибка добавления свойства " . $arFields["NAME"] . "<br />";


            $arFields = [
                "NAME" => "HTML код",
                "ACTIVE" => "Y",
                "SORT" => 300,
                "CODE" => "HTML_CODE",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "HTML",
                "IBLOCK_ID" => $iblockId,];
            $propId = $ibp->Add($arFields);
            if ($propId > 0)
            {
                $arFields["ID"] = $propId;
                $arCommonProps[$arFields["CODE"]] = $arFields;
            }
            else
                echo "&mdash; Ошибка добавления свойства " . $arFields["NAME"] . "<br />";
        }
        $PROPS = [
            [
                "CONNECTED_ID" => 4,
                "CONNECTED_TYPE_ID" => 2,
                "HTML_CODE" => "
              <form action='#'>
                           <label for='name'>Имя:</label>
    <input type='text' id='name' name='name' required><br><br>
    <label for='email'>Email:</label>
    <input type='email' id='email' name='email' required><br><br>
    <input type='submit' value='Отправить'>
  </form>
            ",
            ],
            [
                "CONNECTED_ID" => 5,
                "CONNECTED_TYPE_ID" => 2,
                "HTML_CODE" => "
              <h1>Список элементов</h1>
  <ul>
    <li>Элемент 1</li>
    <li>Элемент 2</li>
    <li>Элемент 3</li>
  </ul>
  <button onclick='alert('Кнопка нажата')'>Нажми меня</button>
",
            ],
            [
                "CONNECTED_ID" => 2,
                "CONNECTED_TYPE_ID" => 1,
                "HTML_CODE" => " 
  <h1>Таблица данных</h1>
  <table border='1'>
    <thead>
      <tr>
        <th>Имя</th>
        <th>Возраст</th>
        <th>Город</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Иван</td>
        <td>28</td>
        <td>Москва</td>
      </tr>
      <tr>
        <td>Мария</td>
        <td>32</td>
        <td>Питер</td>
      </tr>
      <tr>
        <td>Петр</td>
        <td>24</td>
        <td>Екатеринбург</td>
      </tr>
    </tbody>
  </table>",
            ],
        ];
        foreach ($PROPS as $PROP)
        {
            $arLoadProductArray = [
                "IBLOCK_ID" => $iblockId,
                "PROPERTY_VALUES" => $PROP,
                "NAME" => "Элемент",
                "ACTIVE" => "Y",
            ];
            $listElementId = $el->Add($arLoadProductArray);
        }
    }

    public function UnInstallDB()
    {
        $ib = new \CIBlock;
        $iblockId = \Bitrix\Main\Config\Option::get($this->MODULE_ID, 'test_iblock_id');
        $ib->Delete($iblockId);
    }

    public function InstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Otus\\Homework\\Crm\\Handlers',
            'updateTabs',
        );

        return true;
    }

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            '\\Otus\\Vacation\\Crm\\Handlers',
            'updateTabs',
        );

        return true;
    }
}
