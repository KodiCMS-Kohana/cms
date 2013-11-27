<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Widgets
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_Ajax_Widget extends Controller_Ajax_JSON {

	public function action_list()
	{
		$page_widgets = DB::select('widget_id')
			->from('page_widgets')
			->where('page_id', '=', (int) $this->request->param('id'))
			->execute()
			->as_array('widget_id');

		$res_widgets = ORM::factory('widget');
		
		if(!empty($page_widgets))
			$res_widgets->where('id', 'NOT IN', $page_widgets);
		
		$res_widgets = $res_widgets->find_all();
		$widgets = array();
		
		foreach ($res_widgets as $widget)
		{
			$widgets[$widget->type][$widget->id] = $widget;
		}
		
		$this->json = (string) View::factory( 'widgets/ajax/list', array(
			'widgets' => $widgets
		));
	}
	
	public function action_add()
	{
		$widget_id = (int) $this->request->query('widget_id');
		$page_id = (int) $this->request->query('page_id');
		
		$data = array(
			'page_id' => $page_id, 
			'widget_id' => $widget_id
		);
		
		$insert = DB::insert('page_widgets')
			->columns( array_keys( $data ))
			->values( array_values( $data ))
			->execute();
		
		if($insert)
		{
			$this->json['status'] = TRUE;
			$this->json['message'] = __('Widget added to page');
			$this->json['widget'] = (string) View::factory( 'widgets/ajax/row', array(
				'widget' => ORM::factory('widget')->where( 'id', '=', $widget_id )->find()
			));
		}
		
	}
}