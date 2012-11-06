<?php defined('SYSPATH') or die('No direct access allowed.');

class Archive
{
    public function __construct(&$page, $params)
    {
        $this->page =& $page;
        $this->params = $params;
        
        switch(count($params))
        {
            case 0: break;
            case 1:
                if (strlen((int) $params[0]) == 4)
                    $this->_archiveBy('year', $params);
                else
                    $this->_displayPage($params[0]);
            break;
            
            case 2:
                $this->_archiveBy('month', $params);
            break;
            
            case 3:
                $this->_archiveBy('day', $params);
            break;
            
            case 4:
                $this->_displayPage($params[3]);
            break;
            
            default:
                Model_Page_Front::not_found();
        }
    }
    
    private function _archiveBy($interval, $params)
    {
        $this->interval = $interval;

        $page = $this->page->children(array(
            'where' => array(
				array('behavior_id', '=', 'archive_' . $interval . '_index')
			),
            'limit' => 1
        ), array(), TRUE);
        
        if ($page)
        {
            $this->page = $page;
            $month = isset($params[1]) ? (int)$params[1]: 1;
            $day = isset($params[2]) ? (int)$params[2]: 1;

            $this->page->time = mktime(0, 0, 0, $month, $day, (int)$params[0]);
        }
        else
        {
            Model_Page_Front::not_found();
        }
    }
    
    private function _displayPage($slug)
    {
        if( ($this->page = Model_Page_Front::findBySlug($slug, $this->page)) === false )
		{
            Model_Page_Front::not_found();
		}
    }
    
    public function get()
    {
        $date = join('-', $this->params);
        
        $pages = $this->page->parent->children(array(
            'where' => array(array('page.created_on', 'like', $date . '%')),
            'order' => array(array('page.created_on', 'desc'))
        ));

        return $pages;
    }
    
    public function archivesByYear()
    {
		return DB::select(array(DB::expr( 'DATE_FORMAT('. Database::instance()->quote_column('created_on').', "%Y")' ), 'date'))
			->distinct(TRUE)
			->from(Model_Page::TABLE_NAME)
			->where('parent_id', '=', $this->page->id)
			->where('status_id', '!=', Model_Page_Front::STATUS_HIDDEN)
			->order_by( 'created_on', 'desc' )
			->execute()
			->as_array(NULL, 'date');
    }
    
    public function archivesByMonth()
    {
		return DB::select(array(DB::expr( 'DATE_FORMAT('. Database::instance()->quote_column('created_on').', "%Y/%m")' ), 'date'))
			->distinct(TRUE)
			->from(Model_Page::TABLE_NAME)
			->where('parent_id', '=', $this->page->id)
			->where('status_id', '!=', Model_Page_Front::STATUS_HIDDEN)
			->order_by( 'created_on', 'desc' )
			->execute()
			->as_array(NULL, 'date');
    }
    
    public function archivesByDay()
    {
		return DB::select(array(DB::expr( 'DATE_FORMAT('. Database::instance()->quote_column('created_on').', "%Y/%m/%d")' ), 'date'))
			->distinct(TRUE)
			->from(Model_Page::TABLE_NAME)
			->where('parent_id', '=', $this->page->id)
			->where('status_id', '!=', Model_Page_Front::STATUS_HIDDEN)
			->order_by( 'created_on', 'desc' )
			->execute()
			->as_array(NULL, 'date');
    }
	
} // end class Archive


class PageArchive extends Model_Page_Front
{
	protected function setUrl()
	{
		$this->url = trim($this->parent->url . date('/Y/m/d/', strtotime($this->created_on)). $this->slug, '/');
	}

	public function title() { return isset($this->time) ? strftime($this->title, $this->time): $this->title; }

	public function breadcrumb() { return isset($this->time) ? strftime($this->breadcrumb, $this->time): $this->breadcrumb; }
	
} // end class PageArchive