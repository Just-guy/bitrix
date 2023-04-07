<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Iblock;

if (!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-" => " "));
$objectIblock = Iblock\IblockTable::GetList([
	'select' => ['ID', 'NAME'],
	'filter' => ['IBLOCK_TYPE_ID' => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")],
	'order' => ['SORT' => 'DESC'],
]);
while ($arrayIblock = $objectIblock->fetch()) {
	$finalDataIblock[$arrayIblock['ID']] = $arrayIblock['NAME'];
}

$objectSections = Iblock\SectionTable::getList([
	'select' => ['ID', 'NAME'],
	'filter' => ['=IBLOCK.ID' => $arCurrentValues["IBLOCK_ID"]],
	'order' => ['DATE_CREATE' => 'DESC'],
]);
while ($arraySections = $objectSections->Fetch()) {
	$finalDataSections[$arraySections['ID']] = $arraySections['NAME'];
}

$objectElement = Iblock\ElementTable::getList([
	'select' => ['ID', 'NAME'],
	'filter' => ['=IBLOCK.ID' => $arCurrentValues["IBLOCK_ID"]],
	'order' => ['DATE_CREATE' => 'DESC'],
]);
while ($arrayElement = $objectElement->Fetch()) {
	$finalDataElement[$arrayElement['ID']] = $arrayElement['NAME'];
}

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $finalDataIblock,
			"DEFAULT" => '={$_REQUEST["ID"]}',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),
		"SECTIONS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_SECTIONS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $finalDataSections,
			"SIZE" => 3
		),
		"LIST_OF_ELEMENTS" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("C_LIST_OF_ELEMENTS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $finalDataElement,
			"SIZE" => 10
		),
		"WRITE_YOUR_TITLE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("C_WRITE_YOUR_TITLE"),
			"TYPE" => "STRING",
			"DEFAULT" => ""
		),
		"CACHE_TIME" => array("DEFAULT" => 3600),
	),
);
