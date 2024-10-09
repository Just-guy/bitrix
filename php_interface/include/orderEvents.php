<?

use Bitrix\Main\EventManager;

class EventHandlers
{
	private static $newUserLogin = false;
	private static $newUserPass = false;

	public static function init(): void
	{
		$eventManager = EventManager::getInstance();

		$eventManager->addEventHandler("sale", "OnOrderNewSendEmail", [static::class, 'ModifyOrderSaleMails']);
		$eventManager->addEventHandler('main', 'OnBeforeUserAdd', [static::class, 'OnBeforeUserAddHandler']);
	}

	// ====== Передаём во все шаблоны писем вызываемые событием SALE_NEW_ORDER логин и пароль
	public static function OnBeforeUserAddHandler($arFields)
	{
		self::$newUserLogin = $arFields['EMAIL'];
		self::$newUserPass = $arFields['PASSWORD'];
	}

	public static function ModifyOrderSaleMails($orderID, &$eventName, &$arFields)
	{
		if (self::$newUserPass === false) {
			$arFields['USER_ACCESS'] = '';
		} else {
			$arFields['USER_ACCESS'] = "<br>" . 'Ваш логин: ' . self::$newUserLogin;
			$arFields['USER_ACCESS'] .= "<br>" . 'Ваш пароль: ' . self::$newUserPass;
		}
	}
	// ======
}
