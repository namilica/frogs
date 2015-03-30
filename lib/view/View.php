<?php namespace frogs\view;

class View{
	protected $filePath;
	protected $cachePath;
	protected $compiler;
	public function __construct($filePath, $cachePath){
		$this->filePath = $filePath;
		$this->cachePath = $cachePath;
		$this->compiler = new Compiler($this->filePath, $this->cachePath);
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
