<?php

spl_autoload_register(function ($class){
	$name = explode('\\', $class.'.php');
	$file = LIB_DIR.implode(DIRECTORY_SEPARATOR, $name);
	unset($name[0]);
	$app = APP_DIR.implode(DIRECTORY_SEPARATOR, $name);
	if(file_exists($file))
		include($file);
	else if(file_exists($app))
		include($app);
	else
		throw new Exception("Files for class $class not found in $file or $app");
});
