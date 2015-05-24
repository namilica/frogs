<?php

spl_autoload_register(function ($class){
	$name = explode('\\', $class.'.php');
	if(reset($name)=='app'){
		unset($name[0]);
		$file = APP_DIR.implode(DIRECTORY_SEPARATOR, $name);
	}else{
		$file = LIB_DIR.implode(DIRECTORY_SEPARATOR, $name);
	}
	if(file_exists($file))
		include($file);
	else
		throw new Exception("Files for class $class not found in $file or $app");
});
