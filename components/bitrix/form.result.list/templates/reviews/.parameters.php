<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arCurrentValues */

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock'))
	return;

$arTemplateParameters = array(
	"USE_LAZY_LOAD" => array(
		"NAME" => GetMessage("FR_USE_LAZY_LOAD"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => ""
	),
	"TITLE_BUTTON" => array(
		"NAME" => GetMessage("FR_TITLE_BUTTON"),
		"TYPE" => "STRING",
		"DEFAULT" => "Показать больше"
	),
	"SRC_ALL_REVIEWS" => array(
		"NAME" => GetMessage("FR_SRC_ALL_REVIEWS"),
		"TYPE" => "STRING",
		"DEFAULT" => ""
	),
);
