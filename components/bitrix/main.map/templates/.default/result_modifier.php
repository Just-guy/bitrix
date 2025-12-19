<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$arResult['SECTIONS'] = [];
$lastId = 0;
$classElements = Bitrix\Iblock\Iblock::wakeUp($arParams['IBLOCK_ID'])->getEntityDataClass();
$classSections = Bitrix\Iblock\Model\Section::compileEntityByIblock($arParams['IBLOCK_ID']);

$objectServiceSections = $classSections::getList([
	'order' => [
		'LEFT_MARGIN' => 'asc',
		'SORT' => 'asc'
	],
	'select' => [
		'ID',
		'NAME',
		"IBLOCK_ID",
		"IBLOCK_SECTION_ID",
		'SECTION_PAGE_URL_RAW' => 'IBLOCK.SECTION_PAGE_URL',
		'DETAIL_PAGE_URL' => 'IBLOCK.DETAIL_PAGE_URL',
		"DEPTH_LEVEL",
		"NAME",
		"LEFT_MARGIN",
		"RIGHT_MARGIN",
		"ELEMENT_ID" => "SECTION_ELEMENT.IBLOCK_ELEMENT_ID",
		"ELEMENT_IBLOCK_ID" => "ELEMENT.IBLOCK_ID",
		"ELEMENT_CODE" => "ELEMENT.CODE",
		"ELEMENT_NAME" => "ELEMENT.NAME",
		"ELEMENT_ACTIVE" => "ELEMENT.ACTIVE",
		"ELEMENT_IBLOCK_SECTION_ID" => "ELEMENT.IBLOCK_SECTION_ID",
	],
	'filter' => [
		'ACTIVE' => 'Y',
		'GLOBAL_ACTIVE' => 'Y',
		"ELEMENT_ACTIVE" => "Y",
	],
	'runtime' => [
		new \Bitrix\Main\ORM\Fields\Relations\Reference(
			'SECTION_ELEMENT',
			\Bitrix\Iblock\SectionElementTable::class,
			\Bitrix\Main\ORM\Query\Join::on('this.ID', 'ref.IBLOCK_SECTION_ID')
		),
		new \Bitrix\Main\ORM\Fields\Relations\Reference(
			'ELEMENT',
			$classElements,
			\Bitrix\Main\ORM\Query\Join::on('this.ELEMENT_ID', 'ref.ID')
		),
	],
]);

while ($data = $objectServiceSections->Fetch()) {
	if (!array_key_exists($data["ID"], $arResult["SECTIONS"])) {
		$arrayServiceSections['SECTION_PAGE_URL'] = \CIBlock::ReplaceDetailUrl(
			$data['SECTION_PAGE_URL_RAW'],
			$data,
			true,
			'S'
		);
		$arrayServiceSections['NAME'] = $data['NAME'];
		$arrayServiceSections['DEPTH_LEVEL'] = $data['DEPTH_LEVEL'];
		$arrayServiceSections['LEFT_MARGIN'] = $data['LEFT_MARGIN'];
		$arrayServiceSections['RIGHT_MARGIN'] = $data['RIGHT_MARGIN'];

		$arResult['SECTIONS'][$data["ID"]] = $arrayServiceSections;
	}

	if ($data["IBLOCK_ID"] == $data["ELEMENT_IBLOCK_ID"]) {
		$arrayServiceElement['DETAIL_PAGE_URL'] = \CIBlock::ReplaceDetailUrl(
			$data['DETAIL_PAGE_URL'],
			[
				'ID' => $data['ELEMENT_ID'],
				'CODE' => $data['ELEMENT_CODE'],
				'IBLOCK_SECTION_ID' => $data['ELEMENT_IBLOCK_SECTION_ID']
			],
			true,
			'E'
		);
		$arrayServiceElement["NAME"] = $data["ELEMENT_NAME"];

		$arResult["SECTIONS"][$data["ID"]]["ELEMENTS"][] = $arrayServiceElement;
	}
}
