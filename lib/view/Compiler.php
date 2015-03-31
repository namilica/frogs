<?php namespace frogs\view;

define("TEMPLATE_DIR", \APP_DIR.'/views');
define("CACHE_DIR", \APP_DIR.'/../storage/cache');

class Compiler{
	protected $templateExtension = "html";
	static $filePath = TEMPLATE_DIR;
	static $cachePath = CACHE_DIR;
	function compilePath($file){
		return self::$cachePath.'/'.md5($file);
	}
	function templatePath($file){
		return self::$filePath.'/'.$file.'.'.$this->templateExtension;
	}
	function isExpired($file){
		$templatePath = $this->templatePath($file);
		$compiledPath = $this->compilePath($file);
		if(!file_exists($compiledPath))
			return true;
		else
			return (filemtime($templatePath)>=filemtime($compiledPath));
	}
	function compile($file){
		$templatePath = $this->templatePath($file);
		$compiledPath = $this->compilePath($file);
		file_put_contents($compiledPath, $this->compileString(file_get_contents($templatePath)));
	}
	function compileString($template){
		$result = '';
		foreach (token_get_all($template) as $token){
			$result .= is_array($token) ? $this->parsePHPToken($token, $this->compilers) : $token;
		}
		return $result;
	}
	protected function parsePHPToken($token, $compilers){
		list($id, $content) = $token;
		if ($id == T_INLINE_HTML){
			foreach ($compilers as $type){
				$content = $this->{"compile{$type}"}($content);
			}
		}
		return $content;
	}
	protected $delimiter = ['\{\{', '\}\}'];
	protected $escapedDelimiter = ['\{\{\{', '\}\}\}'];
	protected $controlDelimiter = ['\{%', '%\}'];
	protected $compilers = [
		'Comment',
		'Echo',
		'Control'
	];
	protected function compileComment($template){
		$pattern = sprintf('/%s--(.*?)--%s/', $this->delimiter[0], $this->delimiter[1]);
		return preg_replace($pattern, '<?php /*$1*/ ?>', $template);
	}
	protected function compileEcho($template){
		if(strlen($this->escapedDelimiter[0]) < strlen($this->delimiter[0]))
			return $this->compileEchoEscaped($this->compileEchoUnescaped($template));
		else
			return $this->compileEchoUnescaped($this->compileEchoEscaped($template));
	}
	protected function compileEchoUnescaped($template){
		$pattern = sprintf('/%s\s*(.+)\s*%s/', $this->delimiter[0], $this->delimiter[1]);
		return preg_replace($pattern, '<?php echo $1; ?>', $template);
	}
	protected function compileEchoEscaped($template){
		$pattern = sprintf('/%s\s*(.+)\s*%s/', $this->escapedDelimiter[0], $this->escapedDelimiter[1]);
		return preg_replace($pattern, '<?php echo htmlspecialchars($1); ?>', $template);
	}
	protected function compileControl($template){
		$controls = [
			'foreach',
			'if'
		];
		$compiled = $template;
		foreach ($controls as $type){
			$compiled = $this->{"compileControl{$type}"}($compiled);
		}
		return $compiled;
	}
	protected function compileControlforeach($template){
		$pattern = sprintf('/\t*%s(foreach)\(([^\s]*)\s+as\s+([^\s]*)\)\s*%s/', $this->controlDelimiter[0], $this->controlDelimiter[1]);
		$compiled = preg_replace($pattern, '<?php if(!empty($2)) $1($2 as $3): ?>', $template);
		$pattern = sprintf('/\t*%s\s*(endforeach)\s*%s/', $this->controlDelimiter[0], $this->controlDelimiter[1]);
		$compiled = preg_replace($pattern, '<?php $1; ?>', $compiled);
		return $compiled;
	}
	protected function compileControlif($template){
		$pattern = sprintf('/\t*%s\s*(if|else|elseif)(.*)\s*%s/', $this->controlDelimiter[0], $this->controlDelimiter[1]);
		$compiled = preg_replace($pattern, '<?php $1$2: ?>', $template);
		$pattern = sprintf('/\t*%s\s*(endif)\s*%s/', $this->controlDelimiter[0], $this->controlDelimiter[1]);
		$compiled = preg_replace($pattern, '<?php $1 ?>', $compiled);
		return $compiled;
	}
}
