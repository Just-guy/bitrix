<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

$arTemplateParameters["USE_TITLE"] = [
	"NAME" => GetMessage("T_USE_TITLE"),
	"TYPE" => "CHECKBOX",
	'REFRESH' => 'Y',
	"DEFAULT" => "N",
];

if ($arCurrentValues["USE_TITLE"] == "Y") {
	$arTemplateParameters["TITLE"] = [
		"NAME" => GetMessage("T_TITLE"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	];
}

$arTemplateParameters["DESCRIPTION"] = [
	"NAME" => GetMessage("T_DESCRIPTION"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
];

$arTemplateParameters["PERSONAL_DATA"] = [
	"NAME" => GetMessage("T_PERSONAL_DATA"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
];
