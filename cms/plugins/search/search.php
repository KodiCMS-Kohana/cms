<?php if(!defined('CMS_ROOT')) die;

class Search
{
    public function __construct(&$page, $params)
    {
        $this->page =& $page;
        $this->params = $params;
		
		$this->page->pages = array();
		
		$this->query = isset($_GET['q']) ? $_GET['q'] : NULL;
		
		$this->search();
    }
	
	public function search()
	{
		$query = Record::query('SELECT id FROM page WHERE title LIKE "%'.$this->query.'%"');
		
		$ids = array();
		foreach ( $query as $row )
		{
			$ids[$row['id']] = $row['id'];
		}
		
		$part_query = Record::query('SELECT page_id as id FROM page_part WHERE content_html LIKE "%'.$this->query.'%"');

		foreach ( $part_query as $row )
		{
			if(!isset($ids[$row['id']]))
				$ids[$row['id']] = $row['id'];
		}
		
		foreach ( $ids as $id )
		{
			$ids[$id] = FrontPage::findById($id);
		}
		
		$this->page->pages = $ids;
	}
}