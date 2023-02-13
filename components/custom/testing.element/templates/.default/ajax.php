<? require($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); // Подключаем ядро bitrix
CModule::IncludeModule('iblock');
$el = new CIBlockElement;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$answer = $request["answer"];
$name = $request["name"];
$telephone = $request["telephone"];
$finishResult = $request["finishResult"];
$componentName = $request->getPost('componentName');
$params = $request->getPost('params');

$arParams = \Bitrix\Main\Component\ParameterSigner::unsignParameters($componentName, $params);

$rsSection = \Bitrix\Iblock\SectionTable::getList(array(
	"filter" => array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"DEPTH_LEVEL" => 2,
		"IBLOCK_SECTION_ID" => $arParams["SECTION_ID"],
		"CODE" => "result"
	),
	"select" =>  array("ID", "CODE", "NAME"),
));
while ($ob = $rsSection->fetch()) {
	$arSection[$arParams["SECTION_ID"]] = $ob["ID"];
}

$arLoadProductArray = array(
	"MODIFIED_BY"    => $USER->GetID(),
	"IBLOCK_SECTION_ID" => $arSection[$arParams["SECTION_ID"]],
	"IBLOCK_ID"      => $arParams["IBLOCK_ID"],
	"PROPERTY_VALUES" => [
		"ANSWER_ON_QUESTION" => $answer,
		"TELEPHONE" => $telephone,
		"FINAL_RESULT" => $finishResult
	],
	"NAME"           => $name,
	"ACTIVE"         => "Y",
);

if ($PRODUCT_ID = $el->Add($arLoadProductArray))
	echo "New ID: " . $PRODUCT_ID;
else
	echo "Error: " . $el->LAST_ERROR;
