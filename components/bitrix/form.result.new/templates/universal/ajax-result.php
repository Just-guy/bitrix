<? require($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
CModule::IncludeModule("form");

use Bitrix\Main\SystemException;

$status = [];
$response = [];
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$webFormId = $request->getPost('webFormId');
$fieldValues = $request->getPost('fieldValues');
$arrayInputs = $request->getPost('arrayInputs');
$captchaWord = $request->getPost('captcha_word');
$captchaSid = $request->getPost('captcha_sid');

$response['ARRAY_INPUTS'] = $arrayInputs;

if (!$APPLICATION->CaptchaCheckCode($captchaWord, $captchaSid)) {
	$response["MESSAGE"] = "FORM_CAPTCHA_FALSE";
	$response["RESULT"] = 'false';
	echo CUtil::PhpToJSObject($response, false, true);
	return;
}

$response["CAPTCHA_CODE"] = $APPLICATION->CaptchaGetCode();

if (!$result = CFormResult::Add($webFormId, $fieldValues)) {
	throw new SystemException("Error");
} else {
	$response["MESSAGE"] = 'FORM_TITLE_SUCCESS';
	$response["RESULT"] = 'success';
	CFormCRM::onResultAdded($webFormId, $result);
	CFormResult::SetEvent($result);
	CFormResult::Mail($result);
	echo CUtil::PhpToJSObject($response, false, true);
}
