<?php namespace frogs\model;

use \Exception;
use \frogs\model\Database;

abstract class Model{
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
	function save(){
		$db = new Database(static::modelName(), static::$fields);
		$db->update($this->data);
	}
	static function all(){
		$db = new Database(static::modelName(), static::$fields);
		return $db->all(static::modelName());
	}
	static function modelName($short = FALSE){
		if($short == TRUE)
			return end(explode("\\", get_called_class()));
		else
			return get_called_class();
	}
}
