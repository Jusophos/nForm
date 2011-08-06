<?php
class nFormHTMLGenerator {
	
	protected $_fields;
	protected $_content;
	protected $_global;
	protected $_tabindex = fals;
	
	public static $TYPES;
	
	public function __construct($fields) {
		
		global $NFORM_CONFIG;
		
		self::$TYPES = array(
			
			'mail' => array(
				
				'maxlength' 	=> 255,
				'minlength' 	=> 7,
				'match_regex'	=> ''
			)
			
		);
		
		$this->_fields = $fields;
		$this->_content = '';
		$this->_global = $fields['global'];
		unset($this->_fields['global']);
		
		if (array_key_exists('flags', $NFORM_CONFIG)) {
			
			if (mb_stristr($NFORM_CONFIG['flags'], 'use_tabindex')) {
				
				$this->_tabindex = 0;
			}
		}
		
		foreach($this->_fields as $field_id => $field) {
			
			$this->_generateField($field_id, $field);
		}
	}
	
	public function getContent() {
		
		return $this->_content;
	}
	
	protected function _generateField(&$field_id, &$field) {
		
		global $NFORM_CONFIG;
		
		$this->_processField($field);
		
		// Data management
		$label = $field_id;
		if (array_key_exists('label', $field)) {
			
			$label = $field['label'];
		}
		
		$required = '';
		if (array_key_exists('flags', $field)) {
			
			if (mb_stristr($field['flags'], 'required') !== false) {
				
				$required = '<span class="'.$NFORM_CONFIG['class_prefix'].'required">'.$NFORM_CONFIG['required_symbol'].'</span>';
			}
		}
		
		$type = 'string';
		if (array_key_exists('type', $field)) {
			
			$type = $field['type'];
		}
		
		// Generate
		
		
		$tmp = '<div id="'.$NFORM_CONFIG['class_prefix'].'row_'.$field_id.'" class="'.$NFORM_CONFIG['class_prefix'].'row">'."\r\n";
		
		$tmp.= "\t".'<label for="'.$NFORM_CONFIG['id'].$field_id.'">'.$label.$required.'</label>'."\r\n";
		
		if ($type == 'plaintext') {
		
			$tmp.= "\t".'<textarea';
			
		} else if ($type == 'password') {
		
			$tmp.= "\t".'<input type="password"';
			
		} else {
			
			$tmp.= "\t".'<input type="text"';
		}
		
		
		$tmp.= ' name="'.$field_id.'"';
		$tmp.= ' id="'.$NFORM_CONFIG['id'].$field_id.'"';
		
		if ($this->_tabindex !== false) {
			
			$this->_tabindex++;
			
			$tmp.= ' tabindex="'.$this->_tabindex.'"';
		}
		
		if (array_key_exists('maxlength', $field)) {
			
			$tmp.= ' maxlength="'.$field['maxlength'].'"';
		}
		if (array_key_exists('title', $field)) {
			
			$tmp.= ' title="'.$field['title'].'"';
		}
		
		if ($type == 'plaintext') {
			
			$tmp.= '></textarea>'."\r\n";
			
		} else {
		
			$tmp.= ' />'."\r\n";	
		}
		
		// message
		$tmp.= "\t".'<div class="'.$NFORM_CONFIG['class_prefix'].'message" id="'.$NFORM_CONFIG['id'].'message_'.$field_id.'">';
		$tmp.= '</div>'."\r\n";
		
		$tmp.= '</div>'."\r\n";
		
		$this->_content.= $tmp;
	}
	
	protected function _processField(&$field) {
		
		$this->_processFieldLengths($field);
	}
	
	protected function _processFieldLengths(&$field) {
	
		if (!array_key_exists('maxlength', $field)) {
			
			if (array_key_exists($field['type'], self::$TYPES)) {

				$field['maxlength'] = self::$TYPES[$field['type']]['maxlength'];
			}			
		}
		if (!array_key_exists('minlength', $field)) {
			
			if (array_key_exists($field['type'], self::$TYPES)) {

				$field['minlength'] = self::$TYPES[$field['type']]['minlength'];
			}			
		}	

	}
}

?>