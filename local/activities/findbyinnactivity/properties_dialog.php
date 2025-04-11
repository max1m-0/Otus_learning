<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<tr id="findinn1">
	<td align="right" width="40%"><?= GetMessage("BPAA_COMP_INN") ?>:</td>
	<td width="60%">
		<?=CBPDocument::ShowParameterField("int", 'COMPANY_INN', $arCurrentValues['COMPANY_INN'])?>
	</td>
</tr>
