<?php

use Bitrix\Crm\AddressTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Bizproc\Activity\Mixins\ErrorHandling;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

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
            "COMPANY_NAME" => 0
        ];

		$this->SetPropertiesTypes([
			'COMPANY_INN' => ['Type' => 'int'],
			'COMPANY_NAME' => ['Type' => 'int'],
		]);
	}

	public function execute()
	{
        $token = "9ffbffd56f08dda20ee054b53ce70690389ce6dd";
        $secret = "1e3369aadb7ba4d56011ced1e7027b3de5fc6992";
        $dadata = new Dadata($token,$secret);
        $dadata->init();

        $fields = array("query"=>$this->COMPANY_INN, "count"=>5);
        $responce = $dadata->suggest("party",$fields);

        $companyName = 'Компания не найдена';
        if (!empty($responce['suggestions'])){
            $companyName = $responce['suggestions'][0]['value'];
        }

        $this->COMPANY_NAME = $companyName;

        return CBPActivityExecutionStatus::Executing;
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
