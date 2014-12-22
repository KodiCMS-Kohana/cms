<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Datasource
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Api_Datasource_Document extends Controller_System_API
{
	public function get_headline()
	{
		$ds_id = $this->param('ds_id', NULL, TRUE);
		$page = (int) $this->param('page', 1);
		
		$ds = $this->_get_datasource($ds_id);
		
		$this->response($this->_get_headline($ds, $page));
	}

	public function post_create()
	{
		$ds_id = $this->param('ds_id', NULL, TRUE);
		
		$ds = $this->_get_datasource($ds_id);
		
		$doc = $ds->get_empty_document();

		$doc->read_values($this->params())
			->validate();

		$doc = $ds->create_document($doc);
		
		$this->message('Document created');
		$this->response($doc->values());
	}
	
	public function post_update()
	{
		$ds_id = $this->param('ds_id', NULL, TRUE);
		$id = $this->param('id', NULL, TRUE);
		
		$ds = $this->_get_datasource($ds_id);
		
		$doc = $ds->get_document((int) $id);

		$doc->read_values($this->params())
			->validate();

		$doc = $ds->update_document($doc);
		
		$this->message('Document updated');
		$this->response($doc->values());
	}

	public function post_publish()
	{
		$doc_ids = $this->param('doc', array(), TRUE);
		$ds_id = $this->param('ds_id', NULL, TRUE);

		if(empty($doc_ids))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
				'Error');
		}
		
		$ds = $this->_get_datasource($ds_id);
	
		$ds->publish($doc_ids);
		$this->response($doc_ids);
	}
	
	public function post_unpublish()
	{
		$doc_ids = $this->param('doc', array(), TRUE);
		$ds_id = $this->param('ds_id', NULL, TRUE);

		if(empty($doc_ids))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
				'Error');
		}
		
		$ds = $this->_get_datasource($ds_id);

		$ds->unpublish($doc_ids);
		$this->response($doc_ids);
	}
	
	public function post_remove()
	{
		$doc_ids = $this->param('doc', array(), TRUE);
		$ds_id = $this->param('ds_id', NULL, TRUE);

		if(empty($doc_ids))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
				'Error');
		}
		
		$ds = $this->_get_datasource($ds_id);

		$ds->remove_documents( $doc_ids );
		$this->response($doc_ids);
	}
	
	protected function _get_datasource($ds_id)
	{
		$ds = Datasource_Section::load($ds_id);
		if($ds === NULL)
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Datasource section not found');
		}
		
		return $ds;
	}
	
	protected function _get_headline($ds, $page = 1)
	{
		return (string) $ds->headline()->set_page($page)->render();
	}
}