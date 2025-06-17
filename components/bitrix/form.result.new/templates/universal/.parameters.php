<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	"TITLE" => array(
		"NAME" => GetMessage("U_TITLE"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"DESCRIPTION" => array(
		"NAME" => GetMessage("U_DESCRIPTION"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"OPEN_FORM_IN_MODAL_WINDOW" => array(
		"NAME" => GetMessage("U_OPEN_FORM_IN_MODAL_WINDOW"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
	"NAME_FORM_CALL_BUTTON" => array(
		"NAME" => GetMessage("U_NAME_FORM_CALL_BUTTON"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"SUCCESSFUL_RESULT_SEPARATE_WINDOW" => array(
		"NAME" => GetMessage("U_SUCCESSFUL_RESULT_SEPARATE_WINDOW"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
	"USER_CONSENT" => array()
);

if($arCurrentValues["SUCCESSFUL_RESULT_SEPARATE_WINDOW"] == "Y") {
	$arTemplateParameters["TITLE_SUCCESSFUL_RESULT"] = array(
		"NAME" => GetMessage("U_TITLE_SUCCESSFUL_RESULT"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	$arTemplateParameters["DESCRIPTION_SUCCESSFUL_RESULT"] = array(
		"NAME" => GetMessage("U_DESCRIPTION_SUCCESSFUL_RESULT"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	$arTemplateParameters["TITLE_FAILURE_RESULT"] = array(
		"NAME" => GetMessage("U_TITLE_FAILUREL_RESULT"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	$arTemplateParameters["DESCRIPTION_FAILURE_RESULT"] = array(
		"NAME" => GetMessage("U_DESCRIPTION_FAILURE_RESULT"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
}

