<? require($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
//$APPLICATION->ShowAjaxHead();
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest(); // Получаем объект запроса, это нужно для того чтобы получить параметры переданные ajax'ом через POST запрос

$params = $request->getPost('params');
$componentName = $request->getPost('componentName');
$templateName = $request->getPost('templateName');

if ($params && $componentName) {
	$arParams = \Bitrix\Main\Component\ParameterSigner::unsignParameters($componentName, $params);
	$templateName = ($templateName ?: $arParams["COMPONENT_TEMPLATE"]);

	$APPLICATION->IncludeComponent(
		$componentName,
		$templateName,
		$arParams,
		false
	);
}
