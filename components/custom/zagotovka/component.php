<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Iblock;

if (!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if ($arParams["IBLOCK_ID"] < 1) {
	ShowError("IBLOCK_ID IS NOT DEFINED");
	return false;
}

$arParams['ELEMENT_COUNT'] = intval($arParams['ELEMENT_COUNT']);
if ($arParams['ELEMENT_COUNT'] <= 0) {
	$arParams['ELEMENT_COUNT'] = 3;
}

$arNavParams = [
	'nTopCount' => false,
	"nPageSize" => $arParams['ELEMENT_COUNT'],
	//'bShowAll' => true,
	//'iNumPage' => intval($_REQUEST["page"]),
	//'checkOutOfRange' => true
];
$arNavigation = CDBResult::GetNavParams($arNavParams);
$cacheDependence = array(false, $arNavigation);

if ($this->StartResultCache(false, [$cacheDependence])) {

	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError("IBLOCK_MODULE_NOT_INSTALLED");
		return false;
	}

	if (!empty($arParams['SECTION_3_LEVEL'])) {
		$iblockSectionID = $arParams['SECTION_3_LEVEL'];
	} else if (!empty($arParams['SECTION_2_LEVEL'])) {
		$iblockSectionID = $arParams['SECTION_2_LEVEL'];
	} else {
		$iblockSectionID = $arParams['SECTION_1_LEVEL'];
	}

	$entitySections = Iblock\Model\Section::compileEntityByIblock((int)$arParams['IBLOCK_ID']);
	$objectSections = $entitySections::getList([
		'select' => ['ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL'],
		'filter' => [
			'IBLOCK_ID' => $arParams["IBLOCK_ID"],
			'ID' => $iblockSectionID,
			//'UF_SHOW_OUR_STORE_ON_PAGE' => 1, Кастомное поле раздела
			"ACTIVE" => "Y"
		],
		'order' => ['DATE_CREATE' => 'DESC'],
	]);
	while ($arraySections = $objectSections->Fetch()) {
		//if ($lastPartitionId === $arraySections['IBLOCK_SECTION_ID']) {
		//	$finalArray[$arraySections['IBLOCK_SECTION_ID']][$arraySections['ID']] = $arraySections;
		//} else {

		//$arraySectionsButtons = CIBlock::GetPanelButtons(
		//	$arraySections["IBLOCK_ID"],
		//	$arraySections["ID"],
		//	0,
		//	array("SECTION_BUTTONS" => false, "SESSID" => false)
		//);

		$finalArraySection[$arraySections['ID']] = $arraySections;
		//$finalArraySection[$arraySections['ID']]["EDIT_LINK"] = $arraySectionsButtons["edit"]["edit_element"]["ACTION_URL"];
		//$finalArraySection[$arraySections['ID']]["DELETE_LINK"] = $arraySectionsButtons["edit"]["delete_element"]["ACTION_URL"];
		//}
		//$lastPartitionId = $arraySections['ID'];
	}

	$arResult['SECTION_LIST'] = $finalArraySection;

	$arSort = array("SORT" => "ASC", "DATE_ACTIVE_FROM" => "DESC", "ID" => "DESC");
	$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "SECTION_ID" => $iblockSectionID, "ID" => $arParams['LIST_OF_ELEMENTS'], "ACTIVE" => "Y", "ACTIVE_DATE" => "Y");
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID");
	$arSelect = array_merge($arSelect, $arParams["PROPERTY_LIST"]);

	$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
	$rsElement->NavStart(0);

	if ($arParams["DETAIL_URL"]) {
		$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
	}

	while ($obElement = $rsElement->GetNextElement()) {
		$arElement = $obElement->GetFields();
		$arProps = $obElement->GetProperties();
		$arButtons = CIBlock::GetPanelButtons(
			$arElement["IBLOCK_ID"],
			$arElement["ID"],
			0,
			array("SECTION_BUTTONS" => false, "SESSID" => false)
		);
		$arElement["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
		$arElement["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

		if ($arElement["PREVIEW_PICTURE"]) {
			$arElement["PREVIEW_PICTURE"] = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
		}
		$arResult["ITEMS"][] = $arElement;
	}

	$arResult["NAV_RESULT"] = [
		'NavPageCount' => $rsElement->NavPageCount,
		'NavPageNomer' => $rsElement->NavPageNomer,
		'NavNum' => $rsElement->NavNum,
	];

	$arResult['NAV_STRING'] = $rsElement->GetPageNavStringEx($navComponentObject, "Страницы:");
	unset($arElement, $lastPartitionId, $finalArraySection);

	$this->SetResultCacheKeys(array(
		"ID",
		"IBLOCK_ID",
		"NAME",
		"IBLOCK_SECTION_ID",
		"IBLOCK",
		"SECTION",
		"PROPERTIES",
	));

	$this->IncludeComponentTemplate();
}
