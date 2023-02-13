<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

	$objectSelectedQuestionnaire = \Bitrix\Iblock\SectionTable::getList(array(
		"filter" => array(
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"DEPTH_LEVEL" => 1
		),
		"select" =>  array("ID", "CODE", "NAME"),
	));
	while ($data = $objectSelectedQuestionnaire->fetch()) {
		$arResult["TESTS_DATA"][] = $data;
	}

	$this->IncludeComponentTemplate();
}
