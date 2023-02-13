<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-" => " "));

$arIBlocks = array();
$db_iblock = CIBlock::GetList(array("SORT" => "ASC"), array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")));
while ($arRes = $db_iblock->Fetch()) {
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];
	$iblockID["IBLOCK_ID"] = $arRes["ID"];
}

$rsSection = \Bitrix\Iblock\SectionTable::getList(array(
	'filter' => array(
		'IBLOCK_ID' => $iblockID,
		'DEPTH_LEVEL' => 1,
	),
	'select' =>  array('ID', 'CODE', 'NAME'),
));
while ($ob = $rsSection->fetch()) {
	$arSection[$ob["ID"]] = $ob["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(

		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TL_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TL_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '={$_REQUEST["ID"]}',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),
		"TEST_PAGE_TEMPLATE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TL_TEST_PAGE_TEMPLATE"),
			"TYPE" => "STRING",
			"DEFAULT" => "testing-element.php?ID=#TEST_ID#",
		),
		"CACHE_TIME"  =>  array("DEFAULT" => 3600),

	),
);
