<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */
global $APPLICATION;
if (isset($_POST['AJAX-ACTION']) && $_POST['AJAX-ACTION'] == 'AUTH') {
	$APPLICATION->RestartBuffer();
	header('Content-type: application/json');
	$response = array();
	if ((isset($arResult['ERROR']) && $arResult['ERROR'] === true) || (!empty($arResult['ERROR_MESSAGE']) && isset($arResult['ERROR_MESSAGE']['TYPE']) && $arResult['ERROR_MESSAGE']['TYPE'] == 'ERROR')) {
		$response["STATUS"] = "ERROR";
		$response["MESSAGES"]["LOGIN"] = "";
		$response["MESSAGES"]["PASSWORD"] = "";
		$response["MESSAGES"]["ERROR_AUTH"] = "";
		if ($_POST['USER_LOGIN'] == '') {
			$response["MESSAGES"]["LOGIN"] = "Введите логин";
		}
		if ($_POST['USER_PASSWORD'] == '') {
			$response["MESSAGES"]["PASSWORD"] = "Введите пароль";
		}
		if (!empty($_POST['USER_PASSWORD']) && !empty($_POST['USER_LOGIN'])) {
			$response["MESSAGES"]["ERROR_AUTH"] = "Неверный логин или пароль";
		}
	} else {
		$response["STATUS"] = "OK";
	}
	echo \Bitrix\Main\Web\Json::encode($response);
	die();
}
