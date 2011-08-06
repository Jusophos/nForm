<?php
include_once('system/parser.lib.php');
include_once('system/generator.lib.php');


if (!$parser = nFormParser::byFile('examples/register.nconfig')) {
	
	
}

if (!$parsed = $parser->parse()) {
	
	
}

$NFORM_CONFIG = $parsed['global'];

$html_gen = nFormGenerator::htmlGenerator($parsed);
#header('Content-Type: text/plain; charset=utf-8');
die($html_gen->getContent());


?>