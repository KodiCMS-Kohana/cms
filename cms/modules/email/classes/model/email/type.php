<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Email
 * @category	Model
 * @author		ButscHSter
 */
class Model_Email_Type extends ORM
{
	protected $_primary_key = 'id';

	protected $_foreign_key_suffix = '';
	
	protected $_has_many = array(
		'templates'	=> array(
			'model'	=> 'Email_Template'
		),
	);
	
	public function rules()
	{
		return array(
			'code' => array(
				array('not_empty')
			),
			'name' => array(
				array('not_empty'),
			),
		);
	}
	
	public function filters()
	{
		return array(
			'code' => array(
				array('URL::title', array(':value', '_'))
			),
			'data' => array(
				array('Model_Email_Type::parse_data'),
				array('serialize')
			)
		);
	}
	
	public function labels()
	{
		return array(
			'code' => __('Email type code'),
			'name' => __('Email type name')
		);
	}
	
	public function data()
	{
		return unserialize($this->data);
	}
	
	/**
	 * 
	 * @return array
	 */
	public function default_options()
	{
		return array(
			'default_email' => Config::get('email', 'default'),
			'site_title' => Config::get('site', 'title'),
			'site_description' => Config::get('site', 'description'),
			'base_url' => URL::base(TRUE),
			'current_time' => date('H:i:s'),
			'current_date' => Date::format(date('Y-m-d')),
		);
	}

	/**
	 * 
	 * @return array
	 */
	public function select_array()
	{
		$data = $this->find_all();
		
		$select_types = array();
		
		foreach ($data as $type)
		{
			$select_types[$type->id] = $type->name . '( ' . $type->code . ' )';
		}
		
		return $select_types;
	}

	/**
	 * 
	 * @param array $options
	 * @return boolean
	 */
	public function send( array $options = NULL )
	{
		if ( ! $this->_loaded)
			return FALSE;

		if(empty($options))
		{
			$options = $this->default_options();
		}
		else
		{
			$options = Arr::merge($this->default_options(), $options);
		}
		
		$_options = array();
		foreach ($options as $key => $value)
		{
			$_options['{'.$key.'}'] = $value;
		}
		
		unset($options);
		
		$messages = $this->templates
			->where('status', '=', Model_Email_Template::ACTIVE)
			->find_all();
		
		foreach ($messages as $message)
		{
			$message->send($_options);
		}
		
		return TRUE;
	}
	
	/**
	 * 
	 * @param array $options
	 * @return boolean
	 */
	public function add_to_queue( array $options = NULL )
	{
		if ( ! $this->_loaded)
			return FALSE;
		
		if(empty($options))
		{
			$options = $this->default_options();
		}
		else
		{
			$options = Arr::merge($this->default_options(), $options);
		}
		
		$_options = array();
		foreach ($options as $key => $value)
		{
			$_options['{'.$key.'}'] = $value;
		}
		
		unset($options);
		
		$messages = $this->templates
			->where('status', '=', Model_Email_Template::ACTIVE)
			->find_all();
		
		foreach ($messages as $message)
		{
			$message->add_to_queue($_options);
		}
		
		return TRUE;
	}

	/**
	 * 
	 * @param array $values
	 * @return array
	 */
	public static function parse_data($values)
	{
		if( ! empty($values))
		{
			$keys = $values['key'];
			$names = $values['value'];
			
			$values = array_combine($keys, $names);
			$values = array_unique(array_filter($values));
		}
		
		return $values;
	}
}
