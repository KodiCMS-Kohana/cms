<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Типы виджетов
 *  
 *  - Model_Widget_Decorator_Dashboard_Pagination - для виджетом с организацией постраничной навигации
 *  - Model_Widget_Decorator_Dashboard - для всех остальных типов
 */
class Model_Widget_Dashboard_Skeleton_Widget extends Model_Widget_Decorator_Dashboard {	
	
	/**
	 * Виджет может иметь несколько копий
	 * @var boolean
	 */
	protected $_multiple = TRUE;
	
	/**
	 * При сохранении настроек виджета необходимо обновлять фрейм
	 * @var boolean 
	 */
	protected $_update_settings_page = FALSE;
	
	/**
	 * Размер виджета в относительных величинах
	 * @var array 
	 */
	protected $_size = array(
		'x' => 2, // Ширина
		'y' => 1, // Высота
		'max_size' => array(2, 1), // Максимальные размеры (ш, в)
		'min_size' => array(2, 1) // Минимальные размеры (ш, в)
	);
	
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
}