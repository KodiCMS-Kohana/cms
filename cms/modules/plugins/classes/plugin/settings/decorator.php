<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Plugins
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class Plugin_Settings_Decorator extends Plugin {

	/**
	 *
	 * @var Filter 
	 */
	protected $_filter = NULL;

	/**
	 * Параметры по умолчанию
	 * @return array
	 */
	public function default_settings()
	{
		return array();
	}
	
	/**
	 * Правила валидации параметров плагина
	 * 
	 * @return array
	 */
	public function rules()
	{
		return array();
	}
	
	/**
	 * Заголовки параметров
	 * 
	 * @return array
	 */
	public function labels()
	{
		return array();
	}
	
	/**
	 * Правила фильтрации настроек
	 * @return array
	 */
	public function filters()
	{
		return array();
	}
	
	/**
	 * Параметры плагина
	 * 
	 * @var array
	 */
	protected $_settings = array();
	
	/**
	 * Получение списка параметров
	 * @return array
	 */
	public function settings()
	{
		return Arr::merge($this->default_settings(), $this->_settings);
	}
	
	/**
	 * Получение значения параметра
	 * 
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get( $key, $default = NULL )
	{
		return Arr::get($this->settings(), $key, $default);
	}

	/**
	 * Установка параметра
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return \Plugin_Decorator
	 */
	public function set( $key, $value = NULL )
	{
		$this->_settings[$key] = $this->_filter->field($key, $value);
		
		return $this;
	}

	/**
	 * 
	 * @param array $data
	 * @return \Plugin_Decorator
	 */
	public function set_settings( array $data )
	{
		foreach ($data as $k => $v)
		{
			$this->set($k, $v);
		}
		
		return $this;
	}
	
	/**
	 * Валидация параметров плагина согласно правилам валидации
	 * 
	 * @param array $array
	 * @return boolean|Validation
	 */
	public function validate()
	{
		$validation = Validation::factory( $this->settings() );
		
		foreach ($this->rules() as $field => $rules)
		{
			$validation->rules($field, $rules);
		}
		
		foreach ($this->labels() as $field => $label)
		{
			$validation->label($field, $label);
		}

		if( ! $validation->check() )
		{
			throw new Validation_Exception( $validation );
		}
		
		return $this;
	}
	
	protected function _init()
	{
		$this->_filter = Filter::factory(array(), $this->filters());

		return parent::_init();
	}

	/******************************************
	 *			 Magic methods
	 ******************************************/
	public function __isset($key)
	{
		return isset($this->_settings[$key]);
	}

	public function __set( $key, $value )
	{
		return $this->set( $key, $value );
	}
	
	public function __get( $key )
	{
		return $this->get( $key );
	}
	
	public function __unset( $key )
	{
		unset($this->_settings[$key]);
	}
	
	/**
	 * Сохранение параметров плагина в БД в сериализованном виде
	 * 
	 * @return \Plugin_Decorator
	 */
	abstract public function save_settings();
	
	/**
	 * Загрузка параметров плагина из БД
	 *
	 * @return \Plugin_Decorator
	 */
	abstract protected function _load_settings();
}