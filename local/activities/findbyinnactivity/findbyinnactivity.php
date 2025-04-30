<?php

use Bitrix\Crm\AddressTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Bizproc\Activity\Mixins\ErrorHandling;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Dadata\DadataClient;
use Bitrix\Crm\Service\Container;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

class CBPFindByInnActivity extends CBPActivity
{

	public function __construct($name)
	{
		parent::__construct($name);
		$this->arProperties = [
            'COMPANY_INN' => 0,
            'USER_ID' => "",
            "COMPANY_ID" => 0
        ];

		$this->SetPropertiesTypes([
			'COMPANY_INN' => ['Type' => 'int'],
			'USER_ID' => ['Type' => 'string'],
			'COMPANY_ID' => ['Type' => 'int'],
		]);
	}

	public function execute()
	{

        $this->COMPANY_ID = self::createCompanyWithRequisites($this->COMPANY_INN,self::getNumberInString($this->USER_ID));

        return CBPActivityExecutionStatus::Closed;
	}
    public static function getNumberInString(string $str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }
    public static function getCompanyRequisitesWithInn($unp, array $filter = []): bool|array
    {
        \Bitrix\Main\Loader::includeModule('crm');

        $onlyUpn = [
            'RQ_INN' => $unp,
            'ENTITY_TYPE_ID' => \CCrmOwnerType::Company,
            '>ENTITY_ID' => 0,
        ];

        $filter = $filter ? array_merge($filter, $onlyUpn) : $onlyUpn;

        $request = \Bitrix\Crm\RequisiteTable::getList(
            [
                'filter' => $filter,
                'select' => ['ID', 'ENTITY_ID', 'AD'],
                'runtime' => [
                    new \Bitrix\Main\Entity\ReferenceField(
                        'AD',
                        \Bitrix\Main\Entity\Base::getInstance('\Bitrix\Crm\AddressTable'),
                        ['ref.ENTITY_ID' => 'this.ID'],
                        ['join_type' => 'LEFT']
                    ),
                ],
            ],
        )->fetchAll();

        if (!$request) return false;

        $result = [
            'ID' => $request[0]['ID'],
            'ENTITY_ID' => $request[0]['ENTITY_ID'],
        ];

        foreach ($request as $item)
        {
            if ($item['CRM_REQUISITE_AD_TYPE_ID'] == 6)
            {
                // Юридический адрес
                $result['layerAddress'] = $item['CRM_REQUISITE_AD_ADDRESS_1'];
            }

            if ($item['CRM_REQUISITE_AD_TYPE_ID'] == 11)
            {
                // Почтовый адрес
                $result['postAddress'] = $item['CRM_REQUISITE_AD_ADDRESS_2'];
            }
        }

        return $result;
    }


    public static function createCompanyWithRequisites($inn, $creator)
    {
        // поиск компании по УНП
        $companyRequisites = self::getCompanyRequisitesWithInn($inn);
        $companyId = null;

        if (
            $companyRequisites !== false
            && !empty($companyRequisites['ENTITY_ID'])
        )
        {
            $companyId = $companyRequisites['ENTITY_ID'];
        }
        else
        {
            require_once '/home/c/cg96022/vendor/autoload.php';
            $token = "9ffbffd56f08dda20ee054b53ce70690389ce6dd";
            $secret = "1e3369aadb7ba4d56011ced1e7027b3de5fc6992";
            $dadata = new DadataClient($token, $secret);

            $response = $dadata->suggest("party",$inn);
            $companyInfo = '';
            $companyName = 'Компания не найдена';
            if (!empty($response)){
                $companyName = $response[0]['value'];
                $companyInfo = "Полное имя: {$response[0]['management']['name']}
                <br>Пост: {$response[0]['management']['post']}
                <br>Начал работу: ".date("Y-m-d",$response[0]['management']['start_date'])."
                <br>Уволен: {$response[0]['management']['disqualified']}";
                $companyFactory = Container::getInstance()->getFactory(\CCrmOwnerType::Company);

                $companyItem = $companyFactory->createItem();


                $companyFieldsToCreate = [
                    'TITLE' => $companyName,
                    'COMMENTS' => $companyInfo,
                    'ASSIGNED_BY_ID' => $creator, // обязательный
                    'CREATED_BY' => $creator, // обязательный
                    'UPDATED_BY' => $creator, // обязательный
                ];

                foreach ($companyFieldsToCreate as $fieldName => $fieldValue)
                {
                    $companyItem->set($fieldName, $fieldValue);
                }

                $result = $companyItem->save(false);

                if ($result->isSuccess())
                {
                    $companyId = $companyItem->getId();

                    self::createCompanyRequisites(
                        [
                            'ENTITY_ID' => $companyId,
                            'ENTITY_TYPE_ID' => \CCrmOwnerType::Company,
                            'PRESET_ID' => 8,
                            'NAME' => $companyName,
                            'SORT' => 500,
                            'ACTIVE' => 'Y',
                            'RQ_COMPANY_NAME' => $companyName,
                            'RQ_COMPANY_FULL_NAME' => $companyName,
                            'RQ_INN' => $inn,
                        ],
                        [
                            "ADDRESS_1" => "",
                            "CITY" => "",
                            "POSTAL_CODE" => "",
                            "COUNTRY" => "",
                        ],
                    );
                    $companyItem->save();
                }
            }

        }

        return $companyId;
    }
    public static function createCompanyRequisites(array $fields, array $addressFields = [])
    {
        \Bitrix\Main\Loader::includeModule('crm');

        $entityRequisite = new \Bitrix\Crm\EntityRequisite();

        $requisitesResult = $entityRequisite->add($fields);

        if (!$requisitesResult) return $requisitesResult;
        if ($addressFields && $requisitesResult->isSuccess())
        {
            $addressRequisite = new \Bitrix\Crm\EntityAddress();

            $addressRequisite->register(
                8,
                $requisitesResult->getId(),
                6,
                $addressFields,
            );
        }
        else{
            return false;
        }

        return true;
    }

    public static function GetPropertiesDialogValues($documentType, $activityName, &$arWorkflowTemplate, &$arWorkflowParameters, &$arWorkflowVariables, $arCurrentValues, &$arErrors)
    {
        $arErrors = array();

        if (!$arCurrentValues['COMPANY_INN']) {
            $arErrors[] = array(
                'code' => 'Empty',
                'message' => 'Заполните все поля'
            );
        }

        if (!empty($arErrors)) {
            return false;
        }

        $arProperties = [
            'COMPANY_INN' => $arCurrentValues['COMPANY_INN'],
        ];

        $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName(
            $arWorkflowTemplate,
            $activityName
        );
        $arCurrentActivity['Properties'] = $arProperties;

        return true;
    }

    public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues = null, $formName = "")
    {
        if (!is_array($arCurrentValues))
        {
            $arCurrentValues = array(
                'COMPANY_INN' => '',
                'USER_ID' => '',
            );

            $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName(
                $arWorkflowTemplate,
                $activityName
            );
            if (is_array($arCurrentActivity['Properties'])) {
                $arCurrentValues = array_merge($arCurrentValues, $arCurrentActivity['Properties']);
            }
        }

        $runtime = CBPRuntime::GetRuntime();
        return $runtime->ExecuteResourceFile(
            __FILE__,
            'properties_dialog.php',
            array(
                'arCurrentValues' => $arCurrentValues,
                'formName' => $formName,
            )
        );
    }

}
