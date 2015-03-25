<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Widgets
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Widget extends Controller_System_API {

	public function get_list()
	{
		$page_id = (int) $this->request->param('id');
		
		$page_widgets = DB::select('widget_id')
			->from('page_widgets');
				
		if (!empty($page_id))
		{
			$page_widgets->where('page_id', '=', (int) $this->request->param('id'));
		}

		$ids = $page_widgets->execute()
			->as_array(NULL, 'widget_id');

		$res_widgets = ORM::factory('widget');
		
		if (!empty($ids))
		{
			$res_widgets->where('id', 'NOT IN', $ids);
		}

		$res_widgets = $res_widgets->find_all();
		$widgets = array();
		
		foreach ($res_widgets as $widget)
		{
			$widgets[$widget->type()][$widget->id] = $widget;
		}

		$this->json = (string) View::factory( 'widgets/ajax/list', array(
			'widgets' => $widgets
		));
	}
	
	public function rest_put()
	{
		$widget_id = (int) $this->param('widget_id', NULL, TRUE);
		$page_id = (int) $this->param('page_id', NULL, TRUE);
		
		$data = array(
			'page_id' => $page_id, 
			'widget_id' => $widget_id
		);
		
		$insert = DB::insert('page_widgets')
			->columns( array_keys( $data ))
			->values( array_values( $data ))
			->execute();
		
		if ($insert)
		{
			$this->response((string) View::factory('widgets/ajax/row', array(
				'widget' => Widget_Manager::load($widget_id),
				'page' => ORM::factory('page', $page_id)
			)));

			$this->message('Widget added to page');
		}
	}
	
	public function post_set_template()
	{
		$widget_id = (int) $this->param('widget_id', NULL, TRUE);
		$template = $this->param('template', NULL);

		$widget = Widget_Manager::load($widget_id);

		if ($widget !== NULL)
		{
			$widget->template = empty($template) ? NULL : $template;
			Widget_Manager::update($widget);

			$this->message('Widget template changet to :name', array(
				':name' => $template
			));

			$this->response(TRUE);
			return;
		}

		$this->response(FALSE);
	}
}