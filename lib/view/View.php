<?php namespace frogs\view;

class View{
	protected $compiler;
	protected $data;
	protected $file;
	function __construct(){
		$this->compiler = new Compiler();
	}
	function draw($file, $data = []){
		$compiler = $this->compiler;
		if($compiler->isExpired($file))
			$compiler->compile($file);
		$this->data = $data;
		$this->file = $compiler->compilePath($file);
		$this->drawTemplate();
	}
	private function drawTemplate(){
		if(!empty($this->data))
			foreach($this->data as $key => $value)
				$$key = $value;
		include($this->file);
	}
}
