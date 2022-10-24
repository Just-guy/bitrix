<? require($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$params = $request->getPost('params');
$componentName = $request->getPost('componentName');
$templateName = $request->getPost('templateName');

if ($params && $componentName && $templateName) {
	$arParams = \Bitrix\Main\Component\ParameterSigner::unsignParameters($componentName, $params);

	$APPLICATION->IncludeComponent(
		$componentName,
		$templateName,
		$arParams
	);
}
