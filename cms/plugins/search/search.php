<?php if ( !defined( 'CMS_ROOT' ) ) die;

class Search {

	public function __construct( &$page, $params )
	{
		$this->page = & $page;
		$this->params = $params;

		$this->page->pages = array( );
		
		$search_query_key = Plugin::getSetting('search_query_key', 'search', 'q');

		$this->query = isset( $_GET[$search_query_key] ) ? $_GET[$search_query_key] : NULL;

		$this->search();
	}

	public function search()
	{
		$query = Record::query( 'SELECT id FROM page WHERE title LIKE "%' . $this->query . '%"' );

		$ids = array( );
		foreach ( $query as $row )
		{
			$ids[$row['id']] = $row['id'];
		}
		
		
		$search_only_title = Plugin::getSetting('search_only_title', 'search', 'yes');
		
		if($search_only_title == 'yes')
		{
			$part_query = Record::query( 'SELECT page_id as id FROM page_part WHERE content_html LIKE "%' . $this->query . '%"' );

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