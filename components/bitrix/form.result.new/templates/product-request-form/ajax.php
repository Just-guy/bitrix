<? require($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
CModule::IncludeModule("form");

use Bitrix\Main\SystemException;

global $APPLICATION;

$status = [];
$jsData = [];
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$webFormId = $request->getPost('webFormId');
$fieldValues = $request->getPost('fieldValues');
$arrayInputs = $request->getPost('arrayInputs');

$jsData['ARRAY_INPUTS'] = $arrayInputs;

foreach ($fieldValues as $key => $value) {
	if ($data = unserialize($value)) $fieldValues[$key] = str_replace('<br>', PHP_EOL, $data);
}
$resultId = CFormResult::Add($webFormId, $fieldValues);
if (!empty($resultId)) {
	CFormResult::Mail($resultId);
	$jsData['RESULT'] = 'success';
}
if (!empty($strError)) $jsData['ERROR'] = $strError;
echo CUtil::PhpToJSObject($jsData, false, true);
unset($key, $value);
