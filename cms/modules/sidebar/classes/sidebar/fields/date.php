<?php defined('SYSPATH') or die('No direct script access.');

class Sidebar_Fields_Date extends Sidebar_Fields_Input {
	
	protected $_template = 'date';
	
	public $_attributes = array(
		'class' => 'datepicker form-control',
		'size' => '10'
	);

	public function __construct(array $field = array()) 
	{
		parent::__construct($field);
	}
}