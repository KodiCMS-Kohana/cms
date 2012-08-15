<?php if(!defined('CMS_ROOT')) die;

class ArchiveController extends PluginController 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setLayout('backend');
	}
	
	public function index($page_id) 
	{
		$page = Record::findByIdFrom('Page', (int) $page_id);
		
		if ( ! $page)
        {
            Flash::set('error', __('Page not found!'));
            redirect(get_url('page'));
        }
		
		use_helper('Pager');
                
                $total_items = Record::countFrom('Page', 'parent_id = ' . (int) $page_id);

		$pager = new Pager(array(
			'items_per_page' => 15,
			'base_url' => '/admin/plugin/archive/'.$page->id.'?page={page}',
			'total_items' => $total_items
		));
		
		$items = $pages = Record::findAllFrom('Page', 'parent_id = ' . (int) $page_id . ' ORDER BY created_on DESC' . $pager->sql_limit);
		$this->display('archive/views/index', array(
			'items' => $items,
			'page'	=> $page,
			'pager' => $pager,
            'total' => $total_items
		));
	}
	
}