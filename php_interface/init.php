<?

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/orderEvents.php")) {
	include_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/orderEvents.php");
	EventHandlers::init();
}
