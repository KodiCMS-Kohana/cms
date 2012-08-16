<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Search {

	public function __construct( &$page, $params )
	{
		$this->page = & $page;
		$this->params = $params;

		$this->page->pages = array( );
		
		$search_query_key = Plugin::getSetting('search_query_key', 'search', 'q');

		$this->query = Arr::get($_GET, $search_query_key);

		$this->search();
	}

	public function search()
	{
		$query = DB::select('id')
			->from('page')
			->where('title', 'like', '%'.$this->query . '%')
			->execute();

		$ids = array( );
		foreach ( $query as $row )
		{
			$ids[$row['id']] = $row['id'];
		}
		
		
		$search_only_title = Plugin::getSetting('search_only_title', 'search', 'yes');
		
		if($search_only_title == 'yes')
		{
			$part_query = Record::query( 'SELECT page_id as id FROM page_part WHERE content_html LIKE "%' . $this->query . '%"' );
			
			DB::select(array('page_id', 'id'))
				->from('page_part')
				->where('content_html', 'like', '%'.$this->query . '%')
				->execute();

			foreach ( $part_query as $row )
			{
				if ( !isset( $ids[$row['id']] ) )
				{
					$ids[$row['id']] = $row['id'];
				}
			}
		}

		foreach ( $ids as $id )
		{
			$ids[$id] = FrontPage::findById( $id );
		}

		$this->page->pages = $ids;
		$this->page->total_found = count($ids);
		$this->page->search_query = $this->query;
	}

}