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

$arComponentParameters = [
	"GROUPS" => [],
	"PARAMETERS" => [
		"IBLOCK_TYPE" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "",
			"REFRESH" => "Y",
		],
		"IBLOCK_ID" => [
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $finalDataIblock,
			"DEFAULT" => '={$_REQUEST["ID"]}',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		],
		"SECTION_1_LEVEL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_SECTION_1_LEVEL"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $finalDataSectionsLevel1,
			"SIZE" => 5,
			"REFRESH" => 'Y',
		),
		"SECTION_2_LEVEL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_SECTION_2_LEVEL"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $finalDataSectionsLevel2,
			"SIZE" => 5,
			"REFRESH" => 'Y',
		),
		"SECTION_3_LEVEL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_SECTION_3_LEVEL"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $finalDataSectionsLevel3,
			"SIZE" => 5,
			"REFRESH" => 'Y',
		),
		"LIST_OF_ELEMENTS" => [
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("C_LIST_OF_ELEMENTS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $finalDataElement,
			"SIZE" => 10
		],
		"WRITE_YOUR_TITLE" => [
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("C_WRITE_YOUR_TITLE"),
			"TYPE" => "STRING",
			"DEFAULT" => ""
		],
		"PROPERTY_LIST" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("C_PROPERTY_LIST"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $finalPropertyArray,
			"SIZE" => 10,
		),
		"CACHE_TIME" => ["DEFAULT" => 3600],
	],
];
