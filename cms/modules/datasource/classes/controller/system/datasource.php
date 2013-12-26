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
	protected $_ds = NULL;

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
	protected function _get_ds($id)
	{
		if(!empty($this->_ds)) return $this->_ds;
		
		$id = (int) $id;
		
		$this->_ds = Datasource_Data_Manager::load($id);
		
		if(empty($this->_ds))
		{
			throw new HTTP_Exception_404('Datasource ID :id not found', 
					array(':id' => $id));
		}
		
		return $this->_ds;
	}
}