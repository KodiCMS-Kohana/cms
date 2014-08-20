<?php defined('SYSPATH') or die('No direct script access.');

class Sidebar_Fields_Input extends Sidebar_Fields_Abstract {
	
	protected $_template = 'input';
	
	public $_attributes = array(
		'class' => 'form-control'
	);

	protected $_options = array(
		'value', 'name', 'label'
	);
	
	public $_field = array(
		'value' => NULL
	);
}