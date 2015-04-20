<?php namespace frogs\view;

class View{
	protected $compiler;
	protected $data;
	protected $file;
	function __construct(){
		$this->compiler = new Compiler();
	}
	function render($file, $data = []){
		$compiler = $this->compiler;
		if($compiler->isExpired($file))
			$compiler->compile($file);
		$this->data = $data;
		$this->file = $compiler->compilePath($file);
		$this->drawTemplate();
	}
	private function drawTemplate(){
		if(!empty($this->data))
			extract($this->data, EXTR_OVERWRITE);
		include($this->file);
	}
}
