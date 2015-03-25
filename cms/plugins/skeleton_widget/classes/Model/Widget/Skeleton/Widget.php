<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Типы виджетов
 *  
 *  - Model_Widget_Decorator_Pagination - для виджетом с организацией постраничной навигации
 *  - Model_Widget_Decorator_Handler - для виджетов обработчиков
 *  - Model_Widget_Decorator - для всех остальных типов
 */
class Model_Widget_Skeleton_Widget extends Model_Widget_Decorator {

	/**
	 * Виджет явялется обработчиком
	 * @var bool 
	 */
	protected $_is_handler = FALSE;

	/**
	 * Виджет использует шаблон
	 * @var boolean 
	 */
	protected $_use_template = TRUE;
	
	/**
	 * Виджет можно кешировать
	 * @var boolean 
	 */
	protected $_use_caching = TRUE;
	
	/**
	 * Настройки по умолчанию для backend
	 * <php echo $widget->height; ?>
	 * 
	 * @var type 
	 */
	protected $_data = array(
		'height' => 500
	);
	
	/**
	 * Метод используется для сохранения настроек виджета. 
	 * Вызывается в момент сохранения виджета.
	 * 
	 * Допустим 
	 * $data = array(
	 *      'param' => '....',
	 *		'param1' => '123'
	 *      .....
	 * );
	 * 
	 * Если для ключа массива имеется метод set_{$key}, то будет вызван метод,
	 * в который будет передано значение, например 
	 * для 'param1' => '123' будет произведен поиск метода set_param1 и в случае
	 * успеха выполнен $this->param1 = $this->set_param1(123);
	 * 
	 * Если метод не найден, то будет произведено обычное присвоение
	 * $this->param = ....;
	 * 
	 * @param array $data
	 */
	public function set_values(array $data)
	{
		$data['param'] = (bool) Arr::get($data, 'param');
		
		return parent::set_values($data);
	}
	
	/**
	 * Данная функция будет запущена если в метод set_values был передан массив
	 * 
	 *  $data = array(
	 *		'param1' => '123'
	 *      .....
	 *  );
	 * 
	 * @param type $value
	 */
	public function set_param1($value /* = 123 */)
	{
		return /* $this->param1 = */ (int) $value;
	}

	/**
	 * Метод запускается после того, как страница была найдена, виджеты 
	 * проинициализированы, до вывода шаблона страницы.
	 * 
	 * @see Context::init_widgets()
	 */
	public function on_page_load() 
	{
		parent::on_page_load();
		
		//....
		
		// В этот момент можно например произвести изменения в странице, передать 
		// 
		// $page = $this->_ctx->get_page();
		// $page->...
	}
	
	/**
	 * Метод изменения хлебных крошек страницы. Вызывается в момент передачи
	 * виджетов на страницу сайта.
	 *  
	 * @see Context::build_crumbs()
	 * @param Breadcrumbs $crumbs
	 */
	public function change_crumbs(Breadcrumbs &$crumbs)
	{
		// $crumbs->add('Test breadcrumb', '...');
	}

	/**
	 * Параметры, которые будут переданы во frontend шаблон
	 * 
	 * @return array [$data]
	 */
	public function fetch_data()
	{
		return array(
			'data' => '...'
		);
	}
	
	/**
	 * Параметры, которые будут переданы во backend шаблон
	 * 
	 * @see Model_Widget_Decorator::fetch_backend_content()
	 * @return array
	 */
	public function backend_data()
	{
		return array(
			'data' => '...'
		);
	}
	
	/**
	 * Получение идентификатора кеша. Используется для кеширования виджета.
	 * 
	 * Каждый виджет имеет свой, уникальный идентификатор кеша, из которого он 
	 * получает закешированный HTML шаблон, если ваш виджет в зависимости от параметров
	 * URL или любых других параметров должен изменять данные, то их необходимо указать
	 * в строке идентификатора кеща.
	 * 
	 * @return string
	 */
	public function get_cache_id()
	{
		return 'Widget::' . $this->type() . '::' . $this->id;
	}
}