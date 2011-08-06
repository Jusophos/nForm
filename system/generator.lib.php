<?php
require_once(dirname(__FILE__).'/html.generator.lib.php');

class nFormGenerator {
	
	public static function htmlGenerator($fields) {
		
		return new nFormHTMLGenerator($fields);
	}
}

?>