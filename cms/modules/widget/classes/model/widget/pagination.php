<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Pagination extends Model_Widget_Decorator {
	
	protected $_data = array(
		'query_key' => 'page'
	);
	
	public function on_page_load() 
	{
		$this->widget = $this->_ctx->get_widget($this->related_widget_id);

		if(($page_offset = $this->_ctx->request()->query($this->query_key)) !== NULL)
		{
			$this->widget->list_offset = (int) $page_offset-1;
		}
	}
	
	public function load_template_data()
	{
		$widgets = Widget_Manager::get_all_widgets();
		
		$select = array();
		foreach($widgets as $id => $widget)
		{
			$select[$id] = $widget['name'];
		}
		
		return array(
			'select' => $select
		);
	}
	
	public function fetch_data()
	{
		if(!($this->widget instanceof Model_Widget_Decorator)) 
		{
			return FALSE;
		}

		$pagination = Pagination::factory(array(
			'items_per_page' => $this->widget->list_size,
			'total_items' => $this->widget->count_total(),
			'current_page' => array(
				'source' => 'query_string',
				'key' => $this->query_key
			)
		));

		$data = array(
			'total_items' => $pagination->total_items,
			'items_per_page' => $pagination->items_per_page,
			'total_pages' => $pagination->total_pages,
			'current_page' => $pagination->current_page,
			'current_first_item' => $pagination->current_first_item,
			'current_last_item' => $pagination->current_last_item,
			'previous_page' => $pagination->previous_page,
			'next_page' => $pagination->next_page,
			'first_page' => $pagination->first_page,
			'last_page' => $pagination->last_page,
			'offset' => $pagination->offset,
			'pagination' => $pagination
		);
		
		return $data;
	}
	
	public function get_cache_id()
	{
		$key = '';
		if($this->widget instanceof Model_Widget_Decorator) 
		{
			$key = $this->widget->id . '::' . $this->widget->list_offset;
		}
		
		return 'Widget::' . $this->id . '::' . $key;
	}
}