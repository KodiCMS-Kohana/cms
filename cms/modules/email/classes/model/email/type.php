<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Email
 * @category	Model
 * @author		ButscHSter
 */
class Model_Email_Type extends ORM
{
	protected $_primary_key = 'code';

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
				'URL::title'
			),
			'data' => array(
				'serialize'
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
	
	public function send( array $options = NULL )
	{
		if ( ! $this->_loaded)
			return FALSE;
		
		$default_options = array(
			'default_email_from' => Config::get('email', 'default'),
			'site_title' => Config::get('site', 'title'),
			'server_name' => URL::base(TRUE)
		);

		if(empty($options))
		{
			$options = $default_options;
		}
		else
		{
			$options = Arr::merge($default_options, $options);
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
}
