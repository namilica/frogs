<?php namespace frogs\model;

use \PDO;
use \Exception;

class Database{
	static $database = DB_DIR.'development.sqlite3';
	private $connection;
	private $class;
	private $table;
	private $fields;
	function __construct($class, $fields){
		$this->connection = new PDO('sqlite:'.static::$database);
		$this->class = $class;
		$this->table = $class::modelName(TRUE).'s';
		foreach($fields as $field)
			$this->fields[':'.$field] = $field;
		return $this;
	}
	function all($class){
		$query = "SELECT * from $this->table";
		$sth = $this->connection->prepare($query);
		$sth->execute();
		while($entry = $sth->fetch(PDO::FETCH_ASSOC))
			$result[] = new $this->class($entry);
		return $result;
	}
	function update($data){
		$query = "INSERT INTO $this->table(".implode(', ', $this->fields).") VALUES (".implode(', ', array_keys($this->fields)).")";
		$sth = $this->connection->prepare($query);
		$sth->execute($data);
	}
}
