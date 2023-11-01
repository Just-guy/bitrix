<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Iblock;
use Bitrix\Iblock\PropertyTable;

if (!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(["-" => " "]);
$objectIblock = Iblock\IblockTable::GetList([
	'select' => ['ID', 'NAME'],
	'filter' => ['IBLOCK_TYPE_ID' => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")],
	'order' => ['SORT' => 'DESC'],
]);
while ($arrayIblock = $objectIblock->fetch()) {
	$finalDataIblock[$arrayIblock['ID']] = $arrayIblock['NAME'];
}

if (!empty($arCurrentValues['SECTION_3_LEVEL'])) {
	$iblockSectionID = $arCurrentValues['SECTION_3_LEVEL'];
} else if (!empty($arCurrentValues['SECTION_2_LEVEL'])) {
	$iblockSectionID = $arCurrentValues['SECTION_2_LEVEL'];
} else {
	$iblockSectionID = $arCurrentValues['SECTION_1_LEVEL'];
}

$objectSectionsLevel1 = Iblock\SectionTable::getList([
	'select' => ['ID', 'NAME'],
	'filter' => ['IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"], 'DEPTH_LEVEL' => 1],
	'order' => ['DATE_CREATE' => 'DESC'],
]);
while ($arraySectionsLevel1 = $objectSectionsLevel1->Fetch()) {
	$finalDataSectionsLevel1[$arraySectionsLevel1['ID']] = $arraySectionsLevel1['NAME'];
}

$objectSectionsLevel2 = Iblock\SectionTable::getList([
	'select' => ['ID', 'NAME'],
	'filter' => ['IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"], 'DEPTH_LEVEL' => 2, 'IBLOCK_SECTION_ID' => $arCurrentValues['SECTION_1_LEVEL']],
	'order' => ['DATE_CREATE' => 'DESC'],
]);
while ($arraySectionsLevel2 = $objectSectionsLevel2->Fetch()) {
	$finalDataSectionsLevel2[$arraySectionsLevel2['ID']] = $arraySectionsLevel2['NAME'];
}

$objectSectionsLevel3 = Iblock\SectionTable::getList([
	'select' => ['ID', 'NAME'],
	'filter' => ['IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"], 'DEPTH_LEVEL' => 3, 'IBLOCK_SECTION_ID' => $arCurrentValues['SECTION_2_LEVEL']],
	'order' => ['DATE_CREATE' => 'DESC'],
]);
while ($arraySectionsLevel3 = $objectSectionsLevel3->Fetch()) {
	$finalDataSectionsLevel3[$arraySectionsLevel3['ID']] = $arraySectionsLevel3['NAME'];
}

$objectElement = Iblock\ElementTable::getList([
	'select' => ['ID', 'NAME'],
	'filter' => ['IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"], 'IBLOCK_SECTION_ID' => $iblockSectionID],
	'order' => ['DATE_CREATE' => 'DESC'],
]);
while ($arrayElement = $objectElement->Fetch()) {
	$finalDataElement[$arrayElement['ID']] = $arrayElement['NAME'];
}

$propertyArray = PropertyTable::getList([
	'select' => ['*'],
	'filter' => ['IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"]],
])->fetchAll();

foreach ($propertyArray as $property) {
	$finalPropertyArray[$property['ID']] = $property['NAME'];
}

$ext = 'wmv,wma,flv,vp6,mp3,mp4,aac,jpg,jpeg,gif,png';

$arComponentParameters = [
	"GROUPS" => [],
	"PARAMETERS" => [
		"IBLOCK_TYPE" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("Z_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "",
			"REFRESH" => "Y",
		],
		"IBLOCK_ID" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("Z_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $finalDataIblock,
			"DEFAULT" => '={$_REQUEST["ID"]}',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		],
		"SECTION_1_LEVEL" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("Z_SECTION_1_LEVEL"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $finalDataSectionsLevel1,
			"SIZE" => 5,
			"REFRESH" => 'Y',
		],
		//"SECTION_2_LEVEL" => [
		//	"PARENT" => "BASE",
		//	"NAME" => GetMessage("Z_SECTION_2_LEVEL"),
		//	"TYPE" => "LIST",
		//	"MULTIPLE" => "N",
		//	"VALUES" => $finalDataSectionsLevel2,
		//	"SIZE" => 5,
		//	"REFRESH" => 'Y',
		//],
		//"SECTION_3_LEVEL" => [
		//	"PARENT" => "BASE",
		//	"NAME" => GetMessage("Z_SECTION_3_LEVEL"),
		//	"TYPE" => "LIST",
		//	"MULTIPLE" => "N",
		//	"VALUES" => $finalDataSectionsLevel3,
		//	"SIZE" => 5,
		//	"REFRESH" => 'Y',
		//],
		"LIST_OF_ELEMENTS" => [
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("Z_LIST_OF_ELEMENTS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $finalDataElement,
			"SIZE" => 10
		],
		"PROPERTY_LIST" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("Z_PROPERTY_LIST"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $finalPropertyArray,
			"SIZE" => 10,
		],
		"DISPLAY_TITLE_ELEMENT" => [
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("Z_DISPLAY_TITLE_ELEMENT"),
			"TYPE" => "STRING",
			"DEFAULT" => ""
		],
		"USE_LAZY_LOAD" => [
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("Z_USE_LAZY_LOAD"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		],
		"NAME_BUTTON_LAZY_LOAD" => [
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("Z_NAME_BUTTON_LAZY_LOAD"),
			"TYPE" => "STRING",
			"DEFAULT" => "Показать еще"
		],
		"ELEMENT_COUNT" => [
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("Z_ELEMENT_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "15"
		],
		//"SELECT_FILE" => [
		//	"PARENT" => "DATA_SOURCE",
		//	"NAME" => GetMessage("Z_SELECT_FILE"),
		//	"TYPE" => "FILE",
		//	"FD_TARGET" => "F",
		//	"FD_EXT" => $ext,
		//	"FD_UPLOAD" => true,
		//	"FD_USE_MEDIALIB" => true,
		//	"FD_MEDIALIB_TYPES" => array('video', 'sound')
		//],
		"CACHE_TIME" => ["DEFAULT" => 3600000],
	],
];
