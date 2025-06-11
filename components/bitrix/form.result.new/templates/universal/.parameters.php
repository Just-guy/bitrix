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
	"USER_CONSENT" => array()
);
