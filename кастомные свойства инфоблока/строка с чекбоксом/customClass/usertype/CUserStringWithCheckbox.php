<?php

namespace lib\customClass\usertype;

use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Iblock;

/**
 * Реализация свойство «Расписание врача»
 * Class CUserStringWithCheckbox
 * @package lib\usertype
 */

class CUserStringWithCheckbox
{
	/**
	 * Метод возвращает массив описания собственного типа свойств
	 * @return array
	 */
	public function GetUserTypeDescription()
	{
		return array(
			'USER_TYPE_ID' => 'string_with_checkbox', //Уникальный идентификатор типа свойств
			'USER_TYPE' => 'STRING_WITH_CHECKBOX',
			'CLASS_NAME' => __CLASS__,
			'DESCRIPTION' => 'Строка с чекбоксом',
			'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
			'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
			'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],
			'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
		);
	}

	/**
	 * Представление формы редактирования значения
	 * @param $arUserField 
	 * @param $arHtmlControl
	 */
	public static function GetPropertyFieldHtml($arProperty, $value, $arHtmlControl)
	{
		$html = '';
		$fieldName =  htmlspecialcharsbx($arHtmlControl['VALUE']);
		$arValue = $value['VALUE'];
		$inputText = ($arValue["STRING_WITH_INPUT"]) ? $arValue["STRING_WITH_INPUT"] : '';
		$inputCheckbox = ($arValue["STRING_WITH_CHECKBOX"]) ? "checked" : '';

		$checkbox = '<input type="checkbox" id="' . $fieldName . '" name="' . $fieldName . '[STRING_WITH_CHECKBOX]" ' . $inputCheckbox . '/>';
		$input = '<input type="text" name="' . $fieldName . '[STRING_WITH_INPUT]" value="' . $inputText . '"/>';
		$html .= '<div class="answer">';
		$html .= $checkbox;
		$html .= $input;
		$html .= '</div><br/>';

		return $html;

		
	}

	/**
	 * Конвертация данных перед сохранением в БД
	 * @param $arProperty
	 * @param $value
	 * @return mixed
	 */
	public static function ConvertToDB($arProperty, $value)
	{
		if ($value['VALUE']['STRING_WITH_INPUT'] != '') {
			try {
				$value['VALUE'] = serialize($value['VALUE']);
			} catch (Bitrix\Main\ObjectException $exception) {
				echo $exception->getMessage();
			}
		} else {
			$value['VALUE'] = '';
		}

		return $value;
	}

	/**
	 * Конвертируем данные при извлечении из БД
	 * @param $arProperty
	 * @param $value
	 * @param string $format
	 * @return mixed
	 */
	public static function ConvertFromDB($arProperty, $value, $format = '')
	{
		if ($value["VALUE"]["STRING_WITH_INPUT"] != '') {
			try {
				$value["VALUE"] = unserialize(htmlspecialcharsback($value['VALUE']));
				$value["VALUE"]["STRING_WITH_CHECKBOX"] = ($value["VALUE"]["STRING_WITH_CHECKBOX"]) ? "checked" : '';
			} catch (Bitrix\Main\ObjectException $exception) {
				echo $exception->getMessage();
			}
		}

		return $value;
	}
}
