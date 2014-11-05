<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Users
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_User_Profile extends ORM {

	protected $_created_column = array(
		'column' => 'created_on',
		'format' => 'Y-m-d H:i:s'
	);

    protected $_has_one = array(
		'user' => array('model' => 'user'),
    );

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 32))
			),
		);
	}
	
	public function labels()
	{
		return array(
			'name'			=> __('Name'),
			'locale'		=> __('Interface language')
		);
	}
	
	public function form_columns()
	{
		return array(
			'locale' => array(
				'type' => 'select',
				'choices' => array($this, 'get_available_langs')
			)
		);
	}
	
	public function get_available_langs()
	{
		$langs = I18n::available_langs();
		$system_default = Arr::get($langs, Config::get('site', 'default_locale'));

		$langs[Model_User::DEFAULT_LOCALE] = __('System default (:locale)', array(
			':locale' => $system_default
		));

		return $langs;
	}
}