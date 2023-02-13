<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$last = null;
foreach ($arResult["TEST_DATA"] as $key => $answer) {
	if (!empty($answer["PROPERTY_LIST_ANSWER_VALUE"]["STRING_WITH_INPUT"])) {
		if ($answer["ID"] == $last) {
			if (!empty($answer["PROPERTY_LIST_ANSWER_VALUE"]["STRING_WITH_CHECKBOX"])) $arAnswer[$lastKey]["CORRECT"] = $answer["PROPERTY_LIST_ANSWER_VALUE_ID"];
			$arAnswer[$lastKey]["ANSWER"][$answer["PROPERTY_LIST_ANSWER_VALUE_ID"]] = $answer["PROPERTY_LIST_ANSWER_VALUE"]["STRING_WITH_INPUT"];
		} else {
			if (!empty($answer["PROPERTY_LIST_ANSWER_VALUE"]["STRING_WITH_CHECKBOX"])) $arAnswer[$key]["CORRECT"] = $answer["PROPERTY_LIST_ANSWER_VALUE_ID"];
			$arAnswer[$key]["IMAGE"] = CFile::GetPath($answer["PROPERTY_IMAGE_VALUE"]);
			$arAnswer[$key]["QUESTION"] = $answer["NAME"];
			$arAnswer[$key]["ANSWER"][$answer["PROPERTY_LIST_ANSWER_VALUE_ID"]] = $answer["PROPERTY_LIST_ANSWER_VALUE"]["STRING_WITH_INPUT"];
			$lastKey = $key;
		}
		$last = $answer["ID"];
	}
}
$arAnswer = array_values($arAnswer);
$arResult["ANSWER"] = $arAnswer;