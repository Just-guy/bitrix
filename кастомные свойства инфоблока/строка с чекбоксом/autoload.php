<?
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(null, [
	'customClass\likeSystem' => APP_CLASS_FOLDER . '/customClass/likeSystemClass.php',
	'customClass\dataRecaptchaV3' => APP_CLASS_FOLDER . '/customClass/dataRecaptchaV3.php',
	'lib\customClass\usertype\CUserStringWithCheckbox' => APP_CLASS_FOLDER . 'customClass/usertype/CUserStringWithCheckbox.php',
]);
