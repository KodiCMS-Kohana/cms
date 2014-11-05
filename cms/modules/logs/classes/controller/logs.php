<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Logs
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Logs extends Controller_System_Backend {
	
	public function before()
	{
		parent::before();

		$this->breadcrumbs
			->add(__('Logs'), Route::get('backend')->uri(array('controller' => 'logs')));
	}
	
	public function action_index()
	{
		$logs = ORM::factory('log')->filter();
		
		Assets::css('logs', 'cms/media/css/controller/logs.css');

		$per_page = (int) Arr::get($this->request->query(), 'per_page', 20);
		$pager = Pagination::factory(array(
			'total_items' => $logs->reset(FALSE)->count_all(),
			'items_per_page' => $per_page
		));
		
		$sidebar = new Sidebar(array(
			new Sidebar_Fields_DateRange(array(
				'label' => __('Date range'),
				'name' => 'created_on',
				'range' => array(
					array(
						'name' => '',
						'value' => Arr::path($this->request->query(), 'created_on.0')
					),
					array(
						'name' => '',
						'value' => Arr::path($this->request->query(), 'created_on.1')
					)
				)
			)),
			new Sidebar_Fields_Select(array(
				'name' => 'level[]',
				'label' => __('Log level'),
				'options' => Log::levels(),
				'selected' => (array) $this->request->query('level')
			)),
			new Sidebar_Fields_Input(array(
				'name' => 'per_page',
				'label' => __('Items per page'),
				'value' => $per_page,
				'size' => 3
			))
		));
		
		$this->set_title(__('Logs'), FALSE);

		$this->template->content = View::factory( 'logs/index', array(
			'logs' => $logs
				->limit($pager->items_per_page)
				->offset($pager->offset)
				->find_all(),
			'pager' => $pager,
			'sidebar' => $sidebar
		));
	}
}