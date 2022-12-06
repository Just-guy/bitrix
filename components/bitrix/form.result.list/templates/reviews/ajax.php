<? require($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); // Подключаем ядро bitrix

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest(); // Получаем объект запроса, это нужно для того чтобы получить параметры переданные ajax'ом через POST запрос

$params = $request->getPost('params');               // Получаем сериализованные параметры компонента переданные через POST запрос
$componentName = $request->getPost('componentName'); // Получаем название компонента
$templateName = $request->getPost('templateName');   // Получаем название шаблона компонента

if ($params && $componentName && $templateName) {                                                // Если все занчения получены, то
	$arParams = \Bitrix\Main\Component\ParameterSigner::unsignParameters($componentName, $params); // принимает одну сериализованную переменную и конвертирует её обратно в значение PHP

// Вызываем комопонент form.result.list
	$APPLICATION->IncludeComponent(
		$componentName,
		$templateName,
		$arParams
	);
}