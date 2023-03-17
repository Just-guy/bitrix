<?

namespace lib\customClass\usertype;

use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Iblock;

/**
 * Class CUser2StringToRow
 * @package lib\usertype
 */

class CUser2StringInRow
{
	/**
	 * Метод возвращает массив описания собственного типа свойств
	 * @return array
	 */
	public function GetUserTypeDescription()
	{
		return array(
			'USER_TYPE_ID' => 'two_string_in_row', //Уникальный идентификатор типа свойств
			'USER_TYPE' => 'TWO_STRING_IN_ROW',
			'CLASS_NAME' => __CLASS__,
			'DESCRIPTION' => 'Две строки в ряд',
			'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
			'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
			'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],
			'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
		);
	}

	/**
	 * Конвертация данных перед сохранением в БД
	 * @param $arProperty
	 * @param $value
	 * @return mixed
	 */
	public static function ConvertToDB($arProperty, $value)
	{
		if (!empty($value['VALUE'])) {
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
		if ($value['VALUE'] != '') {
			try {
				$value["VALUE"] = unserialize(htmlspecialcharsback($value['VALUE']));
			} catch (Bitrix\Main\ObjectException $exception) {
				echo $exception->getMessage();
			}
		}
		return $value;
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
		$inputText1 = ($arValue["TWO_STRING_IN_ROW_1"]) ? $arValue["TWO_STRING_IN_ROW_1"] : '';
		$inputText2 = ($arValue["TWO_STRING_IN_ROW_2"]) ? $arValue["TWO_STRING_IN_ROW_2"] : '';

		$input1 = '<input type="text" id="' . $fieldName . '" name="' . $fieldName . '[TWO_STRING_IN_ROW_1]" value="' . $inputText1 . '"/>';
		$input2 = ' <input type="text" name="' . $fieldName . '[TWO_STRING_IN_ROW_2]" value="' . $inputText2 . '"/>';
		$html .= '<div class="2-strings-in-row">';
		$html .= $input1;
		$html .= $input2;
		$html .= '</div><br/>';

		return $html;
	}
}
