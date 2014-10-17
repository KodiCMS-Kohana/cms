<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Widgets
 * @category	Decorator
 * @author		ButscHSter
 */
abstract class Model_Widget_Decorator_Handler extends Model_Widget_Decorator {

	protected $_use_template = FALSE;
	protected $_use_caching = FALSE;
	protected $_is_handler = TRUE;
	
	protected $_as_json = FALSE;
	
	protected $_response = array(
		'status' => FALSE,
		'errors' => array(),
		'data' => array()
	);
	
	public function on_page_load() 
	{
		$this->send_response();
	}

	public function fetch_data() 
	{ 
		return array();
	}

	public function render(array $params = array()) 
	{
		return $this->on_page_load();
	}
	
	public function link()
	{
		return Route::get('handler')->uri(array('id' => $this->id));
	}
	
	public function send_response()
	{
		if($this->is_ajax())
		{
			$this->ajax_response();
		}
		else
		{
			$this->html_response();
		}
	}

	public function html_response()
	{
		Flash::set('handler_response', $this->_response);
		HTTP::redirect(Request::current()->referrer());
	}
	
	public function ajax_response()
	{
		Request::initial()->headers('Content-type', 'application/json');
		$this->_ctx->response()->body(json_encode($this->_response));
	}
	
	public function is_ajax()
	{
		return (Request::initial()->is_ajax() OR $this->_as_json);
	}
}