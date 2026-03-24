<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Iblock\Iblock;
use Bitrix\Iblock\SectionTable;

// Получаем DataClass для элементов инфоблока
$classElements = Iblock::wakeUp($arParams['IBLOCK_ID'])->getEntityDataClass();

// 1. Получаем все активные разделы
$arResult['SECTIONS'] = [];

$sectionsRes = SectionTable::getList([
	'order' => [
		'LEFT_MARGIN' => 'asc',
		'SORT' => 'asc'
	],
	'filter' => [
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'ACTIVE' => 'Y',
		'GLOBAL_ACTIVE' => 'Y',
	],
	'select' => [
		'ID',
		'NAME',
		'CODE',
		'IBLOCK_ID',
		'DEPTH_LEVEL',
		'LEFT_MARGIN',
		'RIGHT_MARGIN',
		'IBLOCK_SECTION_ID'
	],
]);

// Получим шаблон URL для раздела
$iblockSectionUrl = \CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_PAGE_URL');

while ($section = $sectionsRes->fetch()) {
	// Формируем URL раздела
	$section['SECTION_PAGE_URL'] = \CIBlock::ReplaceDetailUrl(
		$iblockSectionUrl,
		$section,
		true,
		'S'
	);
	$arResult['SECTIONS'][$section['ID']] = $section;
	$arResult['SECTIONS'][$section['ID']]['ELEMENTS'] = [];
}

// 2. Для каждого раздела — только активные элементы
foreach ($arResult['SECTIONS'] as $sectionId => &$section) {
	$elementsRes = $classElements::getList([
		'order' => ['SORT' => 'ASC'],
		'filter' => [
			'IBLOCK_ID' => $arParams['IBLOCK_ID'],
			'ACTIVE' => 'Y',
			'IBLOCK_SECTION_ID' => $sectionId,
		],
		'select' => [
			'ID',
			'NAME',
			'CODE',
			'IBLOCK_SECTION_ID',
			'IBLOCK_ID'
		],
	]);
	// Получим шаблон URL для элемента
	$iblockElementUrl = \CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'DETAIL_PAGE_URL');

	while ($element = $elementsRes->fetch()) {
		$element['DETAIL_PAGE_URL'] = \CIBlock::ReplaceDetailUrl(
			$iblockElementUrl,
			$element,
			true,
			'E'
		);
		$section['ELEMENTS'][] = $element;
	}
}
unset($section);
