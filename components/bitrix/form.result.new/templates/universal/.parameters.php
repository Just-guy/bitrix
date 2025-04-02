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
	"NAME_FORM_CALL_BUTTON" => array(
		"NAME" => GetMessage("U_NAME_FORM_CALL_BUTTON"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"LINK_TO_PRIVACY_POLICY" => array(
		"NAME" => GetMessage("U_LINK_TO_PRIVACY_POLICY"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	)
);
