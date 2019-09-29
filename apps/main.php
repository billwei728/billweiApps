<?php
ini_set('display_errors', 1);
define('APP_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('LIB_DIR', dirname(APP_DIR) . DIRECTORY_SEPARATOR . "library" . DIRECTORY_SEPARATOR);

if (defined('MENU_MODE_ADMIN')) {
	define('OPERATION_MODE', MENU_MODE_ADMIN);
} elseif (defined('MENU_MODE_XPAYTT')) {
	define('OPERATION_MODE', MENU_MODE_XPAYTT);
} elseif (defined('MENU_MODE_EEZIEPAY')) {
	define('OPERATION_MODE', MENU_MODE_EEZIEPAY);
}

include_once(LIB_DIR . 'session.php');

$cookie_name = "user";
if (! isset($_SESSION[$cookie_name])) {
	$_SESSION[$cookie_name] = OPERATION_MODE;
	$cookie_value = OPERATION_MODE;
	setcookie($cookie_name, $cookie_value, time() + (86400 * 1), "/"); // 86400 = 1 day
} else {
	if ("admin" != $_SESSION[$cookie_name]) {
		if (OPERATION_MODE != $_SESSION[$cookie_name]) {
			$_SESSION[$cookie_name] = $_COOKIE[$cookie_name];
			$cookie_value = $_COOKIE[$cookie_name];
			setcookie($cookie_name, $cookie_value, time() + (86400 * 1), "/"); // 86400 = 1 day
		}
	}
}

include_once(LIB_DIR . 'app.php');
include_once(HANDLER . 'menu_populate_handler.php');

if (isset($_POST["menu_action"])) {
	include_once(HANDLER . "menu_handler.php");
} else if (isset($_POST["module_action"])) {
	include_once(HANDLER . "module_handler.php");
}

include_once(PAGES . "menu.php");
unset($_SESSION["page"]);
?>