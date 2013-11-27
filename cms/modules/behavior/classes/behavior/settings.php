<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Behavior
 * @author		ButscHSter
 */
class Behavior_Settings {
	
	/**
	 *
	 * @var array 
	 */
	protected $_data = NULL;
	
	/**
	 *
	 * @var Model_Page 
	 */
	protected $_page = NULL;

	public function __construct( $page )
	{
		$this->_page = $page;
	}
	
	public function __toString()
	{
		return (string) $this->render();
	}
	
	/**
	 * 
	 * @param string $key
	 * @return string
	 */
	public function __get( $key )
	{
		return $this->get( $key );
	}
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return string
	 */
	public function get($key, $default = NULL)
	{
		$this->_load();
		
		return Arr::get($this->_data, $key, $default);
	}

	/**
	 * 
	 * @return Behavior_Settings
	 * @throws Kohana_Exception
	 */
	protected function _load()
	{
		if( $this->_page === NULL )
			throw new Kohana_Exception('Page must be loaded');
		
		if( $this->_data === NULL )
		{
			$this->_data = ORM::factory('Page_Behavior_Setting')
				->find_by_page($this->_page)
				->get('data', array());
		}
		
		return $this;
	}
	
	/**
	 * @return View
	 */
	public function render()
	{
		$this->_load();

		return View::factory('behavior/' . $this->_page->behavior_id)
			->set('settings', $this->_data)
			->set('behavior', $this)
			->set('page', $this->_page);
	}
	
}