<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */


foreach ($arResult["arrAnswers"] as $keyAnswer => $valueAnswer) {
	foreach ($valueAnswer as $keyQuestions => $valueQuestions) {
		foreach ($valueQuestions as $key => $value) {
			$arrAnswer[$value["RESULT_ID"]][$value["SID"]] = $value;
			if ($keyQuestions == "DATE") {
				$arrAnswer[$value["RESULT_ID"]]["DATE"] = $valueQuestions;
			}
		}
	}

	foreach ($arResult["arrResults"] as $keyDate => $valueDate) {
		if ($keyAnswer == $valueDate["ID"] && $valueAnswer["DATE_CREATE"] == '') {
			$arrAnswer[$keyAnswer]["DATE"] = $valueDate["TSX_0"];
		}
	}
}

$arResult["arrAnswers"] = $arrAnswer;