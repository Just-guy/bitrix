<?
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(null, [
	'lib\customClass\usertype\CUserStringWithCheckbox' => APP_CLASS_FOLDER . 'customClass/usertype/CUserStringWithCheckbox.php',
]);
