<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Skeleton
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_Photos extends Controller_System_Backend {

	/**
	 * Экшены которые не требуют прав доступа
	 * 
	 * @var array 
	 */
	public $allowed_actions = array();
	
	public function before()
	{
		// Если права доступа необходимо расширить на основания условия, то это
		// необходимо делать до выполнения parent::before()
		if('.....')
		{
			$this->allowed_actions[] = 'index';
		}

		parent::before();
	}

	public function action_index()
	{
		// Подключение media пакета
		Assets::package(array('skeleton'));
		
		// Вывод в глобальный шаблон JavaScript параметра
		// Все параметры проходят через функцию json_encode
		$this->template_js_params['PARAM'] = '...';
		
		$this->set_title(__('Skeleton'));

		$this->template->content = View::factory('skeleton/index', array(
			'param' => '....'
		));
	}
	
	/**
	 * Инициализация медиа данных для всех экшенов
	 */
	public function init_media()
	{
		parent::init_media();
		
		$this->template_js_params['PARAM1'] = '...';
	}
}