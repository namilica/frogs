<?php namespace frogs\model;

use \Exception;

class Model{
	protected $fields = [];
	protected $data = [];
	function __construct($data = []){
		if(!empty($data))
			foreach($data as $name => $value)
				$this->$name = $value;
		return $this;
	}
	function __get($name){
		if(in_array($name, $this->fields))
			return $this->data[$name];
		else
			throw new Exception("$name not found in class");
	}
	function __set($name, $value){
		if(in_array($name, $this->fields))
			$this->data[$name] = $value;
		else
			throw new Exception("$name not found in class");
	}
}
