<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Datasource
 */
class Controller_System_Datasource extends Controller_System_Backend
{
	/**
	 *
	 * @var DataSource_Section 
	 */
	protected $_section = NULL;

	public function before()
	{
		parent::before();

		Assets::js('datasource', ADMIN_RESOURCES . 'js/datasource.js', 'global');
		Assets::css('datasource', ADMIN_RESOURCES . 'css/datasource.css', 'global');
		
		$this->breadcrumbs
			->add(__('Datasources'), Route::url('datasources', array(
				'directory' => 'datasources',
				'controller' => 'data'
			)));
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return DataSource_Section
	 * @throws HTTP_Exception_404
	 */
	public function section( $id = NULL )
	{
		if( $this->_section instanceof DataSource_Section) return $this->_section;
		
		if($id === NULL)
		{
			throw new DataSource_Exception('Datasource section not loaded');
		}
	
		$this->_section = Datasource_Data_Manager::load((int) $id);
		
		if(empty($this->_section))
		{
			throw new HTTP_Exception_404('Datasource ID :id not found', 
					array(':id' => $id));
		}
		
		return $this->_section;
	}
}