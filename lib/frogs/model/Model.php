<?php namespace frogs\model;

use \Exception;
use \frogs\model\Database;

abstract class Model{
	static $fields = [];
	protected $data = [];
	protected $db;
	function __construct($data = []){
		if(!empty($data))
			foreach($data as $name => $value)
				$this->__set($name, $value);
		$this->db = new Database(get_class($this), static::$fields);
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
		$this->db->update($this->data);
	}
	function all(){
		return $this->db->all(get_class($this));
	}
}
