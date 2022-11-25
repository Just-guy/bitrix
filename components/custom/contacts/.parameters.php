<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;

if (!Loader::includeModule('iblock'))
	return;

$boolCatalog = Loader::includeModule('catalog');
CBitrixComponent::includeComponentClass('bitrix:catalog.section');

$propertiesOrderChanged = [
	"ADDRESSES" => array(
		"NAME" => GetMessage("C_ADDRESSES"),
		"TYPE" => "STRING",
		"MULTIPLE" => "Y",
		"VALUES" => "",
	),
	"PHONE_NUMBERS" => array(
		"NAME" => GetMessage("C_PHONE_NUMBERS"),
		"TYPE" => "STRING",
		"MULTIPLE" => "Y",
		"VALUES" => "",
	),
	"EMAIL" => array(
		"NAME" => GetMessage("C_EMAIL"),
		"TYPE" => "STRING",
		"MULTIPLE" => "Y",
		"VALUES" => "",
	),
	"SOCIAL_NETWORKS" => array(
		"NAME" => GetMessage("C_SOCIAL_NETWORKS"),
		"HIDDEN" => "N"
	),
];

foreach ($propertiesOrderChanged as $key => $value) {
	$filter[$key] = $value["NAME"];
}

$arComponentParameters = [
	"GROUPS" => [],
	"PARAMETERS" => [
		"CACHE_TIME" => ["DEFAULT" => 3600],
		"BLOCK_DISPLAY_ORDER" => [
			'NAME' => GetMessage('C_BLOCKS_ORDER'),
			'TYPE' => 'CUSTOM',
			'REFRESH' => 'Y',
			'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'dragdrop_order'),
			'JS_EVENT' => 'initDraggableOrderControl',
			'JS_DATA' => Json::encode($filter),
			'DEFAULT' => ""
		],
		"SOCIAL_NETWORKS_VK" => [
			"NAME" => GetMessage("C_SOCIAL_NETWORKS_VK"),
			"TYPE" => "STRING",
			"VALUES" => "",
		],
		"SOCIAL_NETWORKS_TG" => [
			"NAME" => GetMessage("C_SOCIAL_NETWORKS_TG"),
			"TYPE" => "STRING",
			"VALUES" => "",
		],
		"SOCIAL_NETWORKS_WA" => [
			"NAME" => GetMessage("C_SOCIAL_NETWORKS_WA"),
			"TYPE" => "STRING",
			"VALUES" => "",
		],
	],
];

$arComponentParameters["PARAMETERS"] = array_merge($arComponentParameters["PARAMETERS"], $propertiesOrderChanged);
