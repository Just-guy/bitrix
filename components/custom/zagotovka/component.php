<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if($arParams["IBLOCK_ID"] < 1) {
	ShowError("IBLOCK_ID IS NOT DEFINED");
	return false;
}

if($this->StartResultCache(false, []))
{

	if(!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError("IBLOCK_MODULE_NOT_INSTALLED");
		return false;
	}	

	$arSort= array("SORT" => "ASC", "DATE_ACTIVE_FROM" => "DESC", "ID" => "DESC");
	$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => $arParams['LIST_OF_QUESTIONS'], "ACTIVE" => "Y", "ACTIVE_DATE" => "Y");
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "PREVIEW_PICTURE");

	$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);

	if ($arParams["DETAIL_URL"]) {
		$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
	}

	while($obElement = $rsElement->GetNextElement()) {

		$arElement = $obElement->GetFields();
		if ($arElement["PREVIEW_PICTURE"]) {
			$arElement["PREVIEW_PICTURE"] = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
		} 
		$arResult["ITEMS"][] = $arElement;
	}

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
