<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Skeleton_Template extends Model_Widget_Decorator {

	/**
	 * 
	 * @return array [$param, $param1] 
	 * В скобках указывается список переменных, которые доступны в шаблоне виджета,
	 * для отображения подсказки во вкладке "шаблон" в настройках виджета
	 * 
	 */
	public function fetch_data()
	{
		return array(
			// Переменные, которые попадают в шаблон виджета через класс View
			// и доступны в шаблоне в виде $param, $param2
			'param' => '...',
			'param1'
		);
	}
}