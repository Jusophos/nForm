<?php
class nFormParser {

	public static $parser;
	public static $errors;
	
	public static $VALID_KEYS;
	
	public $content;
	
	protected $block_storage;
	protected $block_key;

	public static function byFile($file) {
		
		if (!@file_exists($file)) {
			
			self::registerError('Could not find file $1.', $file, 'byFile', 'Try another file. It seems that the file does not exist.');
			return false;
		}
		
		if (!($buffer = @file_get_contents($file))) {
			
			self::registerError('Could not open file $1', $file, 'byFile', 'It seems that the file could not be read, because there are missing file permissions.');
			return false;
		}
		
		return self::byContent($buffer);
	}
	
	public static function byContent($content) {
		
		if ($content == false || $content == null) {
			
			return false;
		}
		
		if (self::$parser === null) {
			
			self::$parser = new nFormParser;
		}
		
		self::$parser->content = $content;
		self::$parser->setup();

		return self::$parser;
	}
	
	public static function registerError($text, $file, $function, $solution) {
		
		self::$errors[] = array($text, $object, $function, $solution);
	}
	
	public function __construct() {
		
		self::$VALID_KEYS = array(
			
			// field attributes
			'type',
			'datatype',
			'minlength',
			'maxlength',
			'flags',
			'match_regex',
			'label',
			'title',
			
			// error messages
			'error_length',
			'error_required',
			'error_format',
			
			// config
			'class_prefix',
			'id',
			'required_symbol'
		);
	}
	
	public function setup() {
		

	}
	
	public function parse() {
		
		if (!is_string($this->content)) {
		
			self::registerError('Could not parse.', null, 'parse', 'The content to be parsed is empty or invalid.');
			return false;
		}
		
		if (trim($this->content) == '') {
			
			self::registerError('Could not parse.', null, 'parse', '');
		}
		
		$lines = explode("\n", $this->content);
		
		if (empty($lines)) {
			
			self::registerError('Could not parse.', null, 'parse', 'The content to be parsed does not contain any lines / content');
			return false;
		}
		
		foreach($lines as $line) {
			
			$this->_parseLine($line);
		}
		
		if (empty($this->block_storage)) {
			
			self::registerError('Could not parse.', null, 'parse', 'The content to be parsed does not contain any valid parameters');
		}
		
		return $this->block_storage;
	}
	
	protected function _parseLine(&$line) {
		
		$line = trim($line);
		
		if ($line == '') {
			
			return;
		}
		
		if (mb_stristr($line, ':') === false) {
			
			return;
		}
		
		$tmp 	= explode(':', $line);
		$key	= trim($tmp[0]);
		$value	= trim($tmp[1]);
	
		if ($key == '') {
			
			return;
		}
	
		if ($value == '') {

			$this->block_key = $key;
			return;
		}
		
		if (!$this->_validKey($key)) {

			return;
		}

		$this->block_storage[$this->block_key][$key] = $value;
	}
	
	protected function _validKey(&$key) {
		
		if (in_array($key, self::$VALID_KEYS)) {
			
			return true;
		}
		
		return false;
	}
}

?>