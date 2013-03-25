<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Archive_HL extends Model_Widget_Decorator {

	protected $_data = array(
		'list_offset' => 0,
		'list_size' => 10
	);
	
	public function set_values(array $data)
	{
		$data['list_offset'] = (int) $data['list_offset'];
		$data['list_size'] = (int) $data['list_size'];

		return parent::set_values($data);
	}

	public function get_page()
	{
		return Context::instance()->get_page();
	}

	public function fetch_data()
	{
		$page = $this->get_page();
		
		if(!($page instanceof Model_Page_Behavior_Archive))
		{
			Model_Page_Front::not_found();
		}
		
		$params = $page->archive->params();
		$date = implode('-', $params);
		
		$clause = array(
			'where' => array(array('page.created_on', 'like', $date . '%')),
			'order_by' => array(array('page.created_on', 'desc'))
		);
		
		if($this->list_offset > 0)
		{
			$clause['offset'] = (int) $this->list_offset;
		}
		
		if($this->list_size > 0)
		{
			$clause['limit'] = (int) $this->list_size;
		}

		$pages = $page->parent()->children($clause);

		return array(
			'pages' => $pages
		);
	}
}