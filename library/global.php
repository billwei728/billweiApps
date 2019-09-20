<?php
	define('COMMON', APP_DIR . 'common' . DIRECTORY_SEPARATOR);
	define('CSS', APP_DIR . 'css' . DIRECTORY_SEPARATOR);
	define('FONTS', APP_DIR . 'fonts' . DIRECTORY_SEPARATOR);
	define('IMG', APP_DIR . 'img' . DIRECTORY_SEPARATOR);
	define('JS', APP_DIR . 'js' . DIRECTORY_SEPARATOR);
	define('PAGES', APP_DIR . 'pages' . DIRECTORY_SEPARATOR);

	define('JSFUNCTION', JS . 'function' . DIRECTORY_SEPARATOR);
	define('JSCLIPBOARD', JS . 'clipboard' . DIRECTORY_SEPARATOR);

	define('DBSTORE', LIB_DIR . 'datastore' . DIRECTORY_SEPARATOR);
	define('OBJECTS', LIB_DIR . 'objects' . DIRECTORY_SEPARATOR);
	define('WORKER', LIB_DIR . 'worker' . DIRECTORY_SEPARATOR);
	define('HANDLER', LIB_DIR . 'handler' . DIRECTORY_SEPARATOR);

	define('LOG', dirname(LIB_DIR) . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR);
	define('M_LOG_INFO', "LOG_INFO");
	define('M_LOG_DEBUG', "LOG_DEBUG");
	define('M_LOG_NOTICE', "LOG_NOTICE");
	define('M_LOG_WARN', "GT_LOG_WARN");
	define('M_LOG_WARNING', "GT_LOG_WARNING");
	define('M_LOG_ERROR', "GT_LOG_ERROR");
	define('M_LOG_CRITICAL', "GT_LOG_CRITICAL");
	define('M_LOG_ALERT', "GT_LOG_ALERT");
	define('M_LOG_EMERGENCY', "GT_LOG_EMERGENCY");

	define('STORAGE', dirname(LIB_DIR) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR);
?>