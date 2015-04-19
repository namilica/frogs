<?php namespace frogs\router;

define('ROUTES_FILE', \APP_DIR.'/routes.php');

class Router{
	static $routes = [];
	static function route(){
		if(empty(self::$routes))
			self::$routes = require(ROUTES_FILE);
	}
}
