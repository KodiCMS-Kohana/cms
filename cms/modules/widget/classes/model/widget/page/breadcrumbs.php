<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Page_Breadcrumbs extends Model_Widget_Decorator {
	
	public $exclude = array();

	public function load_template_data()
	{
		return array(
			'pages' => Model_Page_Sitemap::get()->flatten(),
		);
	}
	
	public function set_values(array $data)
	{
		if( empty( $data['exclude'] ))
		{
			$this->exclude = array();
		}

		return parent::set_values($data);
	}

	public function fetch_data()
	{
		$pages = array();

		if( ($page = Context::instance()->get_page()) instanceof Model_Page_Front)
		{
			$pages = Model_Page_Sitemap::get();
			$pages = $pages->find($page->id)->breadcrumbs();
			
			if( !empty($this->exclude))
			{
				foreach($pages as $i => $page)
				{
					if(  in_array( $page['id'], $this->exclude )) unset($pages[$i]);
				}
			}
			
			if(count($pages) == 1) $pages = array();
		}

		return array(
			'pages' => $pages
		);
	}
}