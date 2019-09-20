<?php
Namespace App;

Use App\app;

spl_autoload_register(function($class) 
{
	// echo "Class: (" . $class . ") is needed. <br />";
	// $bReloadCache = ((1===2)? true : false );
	// echo $bReloadCache;
	// echo "<br />";
	app::getInstance()->autoload($class);
});
?>