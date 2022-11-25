<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$arParams["BLOCK_DISPLAY_ORDER"] = explode(",", $arParams["BLOCK_DISPLAY_ORDER"]);

// Очищаем массивы от пустых значений
$arParams["PHONE_NUMBERS"] = array_diff($arParams["PHONE_NUMBERS"], array(''));
$arParams["ADDRESSES"] = array_diff($arParams["ADDRESSES"], array(''));
$arParams["EMAIL"] = array_diff($arParams["EMAIL"], array(''));
$arParams["~PHONE_NUMBERS"] = array_diff($arParams["~PHONE_NUMBERS"], array(''));
$arParams["~ADDRESSES"] = array_diff($arParams["~ADDRESSES"], array(''));
$arParams["~EMAIL"] = array_diff($arParams["~EMAIL"], array(''));

$arParams["SOCIAL_NETWORKS"] = [$arParams["SOCIAL_NETWORKS_VK"], $arParams["SOCIAL_NETWORKS_TG"], $arParams["SOCIAL_NETWORKS_WA"]];

foreach ($arParams["BLOCK_DISPLAY_ORDER"] as $key => $value) {
	$arResult[$value] = $arParams[$value];
}

?>
<pre>
<? var_dump($arResult) ?>
</pre>
<?


// Форматируем номера для тега <a></a>
foreach ($arResult["PHONE_NUMBERS"] as $key => $phoneElementNoClear) {
	// Удаляю все пробелы, переносы строк,круглые скобки
	$phoneElement = preg_replace('/[\s|()|-]/', '', $phoneElementNoClear);

	// Вычисляем длину строки
	$phoneLength = strlen($phoneElement);

	// Вычисляем первую цифру в номере
	$phoneFirstDigit = substr($phoneElement, 0, 1);

	// Вычисляем первую цифру в номере
	$phoneSecondDigit = substr($phoneElement, 1, 1);

	// Если в номере в самом начале отсутствует +7 или 7 или 8, то добавляем +7
	if (($phoneFirstDigit != "+7" || $phoneFirstDigit == "8" || $phoneFirstDigit == "7") && $phoneLength < 11) {
		$phoneElement = "+7" . $phoneElement;
	}
	// Если в номере в самом начале присутствует c 0 до 9, то меняем ее на +7
	else if (preg_match('/[0-9]/', $phoneFirstDigit)) {
		$phoneElement = substr($phoneElement, 1);
		$phoneElement = "+7" . $phoneElement;
	}
	// Если в номере в самом начале присутствует +, то вычисляем цифру которая стоит после него и если это не 7, то меняем ее на +7
	else if ($phoneFirstDigit == "+" && $phoneSecondDigit != 7) {
		$phoneElement = substr($phoneElement, 2);
		$phoneElement = "+7" . $phoneElement;
	}

	$arResult["PHONE_NUMBERS"][$key]["NO_CLEAR"] = $phoneElementNoClear;
	$arResult["PHONE_NUMBERS"][$key]["CLEAR"] = $phoneElement;
}

?>
<pre>
<? var_dump($arResult) ?>
</pre>
<?
