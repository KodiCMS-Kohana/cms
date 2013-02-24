<?php defined('SYSPATH') or die('No direct access allowed.');

class Behavior_Archive extends Behavior_Abstract
{
	
	protected $_routes = array(
		'/<year>/<month>/<day>/<slug>' => array(
			'regex' => array(
				'year' => '[0-9]{4}',
				'month' => '[0-9]{2}',
				'day' => '[0-9]{2}'
			),
			'method' => '_display_page'
		),
		'/<year>/<month>/<day>' => array(
			'regex' => array(
				'year' => '[0-9]{4}',
				'month' => '[0-9]{2}',
				'day' => '[0-9]{2}'
			)
		),
		'/<year>/<month>' => array(
			'regex' => array(
				'year' => '[0-9]{4}',
				'month' => '[0-9]{2}'
			)
		),
		'/<year>' => array(
			'regex' => array(
				'year' => '[0-9]{4}'
			)
		),
		'/<slug>' => array(
			'method' => '_display_page'
		),
		'' => array(
			'method' => '_display_page'
		),
	);

	public function execute()
	{
		if( isset($this->day) )
		{
			return $this->_archive_by('day');
		}
		else if( isset($this->month) )
		{
			return $this->_archive_by('month');
		}
		else if( isset($this->year) )
		{
			return $this->_archive_by('year');
		}
		
		Model_Page_Front::not_found();
	}

	protected function _archive_by($interval)
    {
        $this->interval = $interval;

        $page = $this->_page->children(array(
            'where' => array(
				array('behavior_id', '=', 'archive_' . $interval . '_index')
			),
            'limit' => 1
        ), array(), TRUE);
        
        if ($page)
        {
            $this->_page = $page;
            $this->_page->time = mktime(0, 0, 0, $this->param('month', 1), $this->param('day', 1), $this->param('year'));
        }
        else
        {
            Model_Page_Front::not_found();
        }
    }
    
    protected function _display_page()
    {
		$slug = $this->param('slug');
		if(empty($slug))
		{
			return;
		}

        if(($this->_page = Model_Page_Front::findBySlug($slug, $this->_page)) === FALSE )
		{
            Model_Page_Front::not_found();
		}
    }
    
    public function get()
    {
        $date = join('-', $this->_params);
        
        $pages = $this->_page->parent->children(array(
            'where' => array(array('page.created_on', 'like', $date . '%')),
            'order' => array(array('page.created_on', 'desc'))
        ));

        return $pages;
    }
    
    public function archives_by_year()
    {
		return DB::select(array(DB::expr( 'DATE_FORMAT('. Database::instance()->quote_column('created_on').', "%Y")' ), 'date'))
			->distinct(TRUE)
			->from(Model_Page::TABLE_NAME)
			->where('parent_id', '=', $this->_page->id)
			->where('status_id', '!=', Model_Page::STATUS_HIDDEN)
			->order_by( 'created_on', 'desc' )
			->execute()
			->as_array(NULL, 'date');
    }
    
    public function archives_by_month()
    {
		return DB::select(array(DB::expr( 'DATE_FORMAT('. Database::instance()->quote_column('created_on').', "%Y/%m")' ), 'date'))
			->distinct(TRUE)
			->from(Model_Page::TABLE_NAME)
			->where('parent_id', '=', $this->_page->id)
			->where('status_id', '!=', Model_Page::STATUS_HIDDEN)
			->order_by( 'created_on', 'desc' )
			->execute()
			->as_array(NULL, 'date');
    }
    
    public function archives_by_day()
    {
		return DB::select(array(DB::expr( 'DATE_FORMAT('. Database::instance()->quote_column('created_on').', "%Y/%m/%d")' ), 'date'))
			->distinct(TRUE)
			->from(Model_Page::TABLE_NAME)
			->where('parent_id', '=', $this->_page->id)
			->where('status_id', '!=', Model_Page::STATUS_HIDDEN)
			->order_by( 'created_on', 'desc' )
			->execute()
			->as_array(NULL, 'date');
    }
	
}