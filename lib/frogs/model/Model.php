<?php namespace frogs\model;

use \Exception;

class Model{
	static $fields = [];
	protected $data = [];
	function __construct($data = []){
		if(!empty($data))
			foreach($data as $name => $value)
				$this->__set($name, $value);
		return $this;
	}
	function __get($name){
		if(in_array($name, static::$fields))
			return $this->data[$name];
		else
			throw new Exception("$name not found in class");
	}
	function __set($name, $value){
		if(in_array($name, static::$fields))
			$this->data[$name] = $value;
		else
			throw new Exception("$name not found in class");
	}
}
