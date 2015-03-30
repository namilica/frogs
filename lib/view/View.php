<?php namespace frogs\view;

class View{
	protected $compiler;
	public function __construct(){
		$this->compiler = new Compiler();
	}
	protected $data;
	protected $file;
	public function draw($file, $data = []){
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
