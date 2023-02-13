<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CJSCore::Init(["masked_input"]);
if (!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if ($arParams["IBLOCK_ID"] < 1) {
	ShowError("IBLOCK_ID IS NOT DEFINED");
	return false;
}

if (!CModule::IncludeModule("iblock")) {
	$this->AbortResultCache();
	ShowError("IBLOCK_MODULE_NOT_INSTALLED");
	return false;
}

if ($this->StartResultCache(false)) {
	$idTest = ($_REQUEST["TEST_ID"]) ? $_REQUEST["TEST_ID"] : $arParams["TEST_ID"];

	$rsSection = \Bitrix\Iblock\SectionTable::getList(array(
		"filter" => array(
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"DEPTH_LEVEL" => 2,
			"IBLOCK_SECTION_ID" => $idTest,
			"CODE" => "question"

		),
		"select" =>  array("ID", "CODE", "NAME"),
	));
	while ($ob = $rsSection->fetch()) {
		$arSection["LIST_QUESTIONS_ID"] = $ob["ID"];
	}

	$arTestSort = array("SORT" => "ASC", "DATE_ACTIVE_FROM" => "DESC", "ID" => "DESC");
	$arTestFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "SECTION_ID" => $arSection["LIST_QUESTIONS_ID"]);
	$arTestSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PREVIEW_PICTURE", "PROPERTY_LIST_ANSWER", "PROPERTY_IMAGE");

	$rsElement = CIBlockElement::GetList($arTestSort, $arTestFilter, false, false, $arTestSelect);

	if ($arParams["DETAIL_URL"]) {
		$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
	} 

	while ($obElement = $rsElement->GetNextElement()) {
		$arElement = $obElement->GetFields();

		if ($arElement["PREVIEW_PICTURE"]) {
			$arElement["PREVIEW_PICTURE"] = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
		}

		$arResult["TEST_DATA"][] = $arElement;
	}

	$arResult["TEST_ID"] = $idTest;

	$this->SetResultCacheKeys(array(
		"ID",
		"IBLOCK_ID",
		"NAV_CACHED_DATA",
		"NAME",
		"IBLOCK_SECTION_ID",
		"IBLOCK",
		"LIST_PAGE_URL",
		"~LIST_PAGE_URL",
		"SECTION",
		"PROPERTIES",
	));

	$this->IncludeComponentTemplate();
}
