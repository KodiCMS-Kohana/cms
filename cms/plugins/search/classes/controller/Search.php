<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Search extends Controller_System_Plugin
{
	
	public function action_settings()
	{
		if ( Request::current()->method() == Request::POST )
		{
			$settings = Arr::get($_POST, 'setting', array());
			Plugins::setAllSettings($settings, 'search');
			
			Messages::success( __('Settings has been saved!'));
			$this->go(URL::site('search/settings'));
		}
		
		$this->template->content = View::factory('search/settings', array(
			'settings' => Plugins::getAllSettings('search')
		));
	}
	
	public function action_index()
	{
		$total_pages = DB::select(array('COUNT("*")', 'total'))
			->from('index')
			->execute()
			->get('total', 0);
		
		$pages = DB::select('*')
			->select(array('page.title', 'page_title'))
			->select(array('index.title', 'index_title'))
			->from('index')
			->join( 'page' , 'left')
				->on('index.page_id', '=', 'page.id')
			->as_object()
			->execute();
			
		$this->template->content = View::factory('search/index', array(
			'total_pages' => $total_pages,
			'pages' => $pages
		));
	}
	
	public function action_indexer()
	{
		$pages = Page::findAll(array(
			'where' => 'page.status_id = ' . Page::STATUS_PUBLISHED 
		));


		$indexer = Model_Search_Indexer::instance();

		foreach ( $pages as $page )
		{
			$content = array();
			$parts = PagePart::findByPageId($page->id);
			foreach ( $parts as $part )
			{
				$content[] = $part->content_html;
			}

			$indexer->add($page->id, $page->title, $content, $page->title);
		}
		
		
		$this->go_back();
	}

}