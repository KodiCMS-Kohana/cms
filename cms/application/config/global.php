<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

	'cache' => array(
		'page_parts' => 86400,
		'front_page' => 60,
		'tags' => 86400
	),
	
	'allowed_html_tags' => array(
		'b' => array(), 'strong' => array(),
		'i' => array(), 'em' => array(),
		'ol' => array(), 'ul' => array(), 'li' => array(),
		'p' => array('align' => 1),
		'br' => array(), 'hr' => array(),
		'h3' => array(), 'h4' => array(),
	),

);
