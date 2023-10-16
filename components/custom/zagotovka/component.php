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

if ($this->StartResultCache(false, [])) {

	if (!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError("IBLOCK_MODULE_NOT_INSTALLED");
		return false;
	}

	$entitySections = Iblock\Model\Section::compileEntityByIblock((int)$arParams['IBLOCK_ID']);
	$objectSections = $entitySections::getList([
		'select' => ['ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL'],
		'filter' => [
			'IBLOCK_ID' => $arParams["IBLOCK_ID"],
			//'UF_SHOW_OUR_STORE_ON_PAGE' => 1, Кастомное поле раздела
			"ACTIVE" => "Y"
		],
		'order' => ['DATE_CREATE' => 'DESC'],
	]);
	while ($arraySections = $objectSections->Fetch()) {
		if ($lastPartitionId === $arraySections['IBLOCK_SECTION_ID']) {
			$finalArray[$arraySections['IBLOCK_SECTION_ID']][$arraySections['ID']] = $arraySections;
		} else {
			$finalArray[$arraySections['ID']] = $arraySections;
		}
		$lastPartitionId = $arraySections['ID'];
	}

	if (!empty($arParams['SECTION_3_LEVEL'])) {
		$iblockSectionID = $arParams['SECTION_3_LEVEL'];
	} else if (!empty($arParams['SECTION_2_LEVEL'])) {
		$iblockSectionID = $arParams['SECTION_2_LEVEL'];
	} else {
		$iblockSectionID = $arParams['SECTION_1_LEVEL'];
	}

	$arSort = array("SORT" => "ASC", "DATE_ACTIVE_FROM" => "DESC", "ID" => "DESC");
	$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "SECTION_ID" => $iblockSectionID, "ID" => $arParams['LIST_OF_ELEMENTS'], "ACTIVE" => "Y", "ACTIVE_DATE" => "Y");
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PREVIEW_PICTURE");
	$arSelect = array_merge($arSelect, $arParams["PROPERTY_LIST"]);

	$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);

	if ($arParams["DETAIL_URL"]) {
		$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
	}

	while ($obElement = $rsElement->GetNextElement()) {
		$arElement = $obElement->GetFields();

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

	unset($arElement, $lastPartitionId, $finalArray);

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
