<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class KodiCMS_Meta {
	
	/**
	 * Фабрика создания объекта Meta информации для шаблона
	 * 
	 * @param Model_Page_Front $page
	 * @return \self
	 */
	public static function factory( Model_Page_Front $page )
	{
		return new Meta($page);
	}
	
	/**
	 * Очистка всех существующих записей в объекте Assets, 
	 * который используется для генерации данных
	 * 
	 * Remove all assets data
	 * @return \KodiCMS_Meta
	 */
	public static function clear()
	{
		Assets::remove_css();
		Assets::remove_js();
		Assets::remove_group();
	}
	
	/**
	 * Объект текущей страницы, используется для генерации мета ифнормации
	 *
	 * @var Model_Page_Front 
	 */
	protected $_page = NULL;

	/**
	 * Конструктор
	 * 
	 * В нем генерируется
	 * 
	 *		<title>...</title>
	 *		<meta name="keywords" content="" />
	 *		<meta name="description" content="" />
	 *		<meta name="robots" content="" />
	 *		<meta name="robots" content="" />
	 *		<meta http-equiv="content-type" content="...; charset=utf-8" />
	 * 
	 * Для переопеределения данных используйте
	 * 
	 *		Meta::factory($page)->add(array('name' => 'description', ...));
	 * 
	 * @param Model_Page_Front $page
	 */
	public function __construct( Model_Page_Front $page )
	{
		$this->_page = $page;

		$this
			->title(HTML::chars($this->_page->meta_title()))
			->add(array('name' => 'keywords', 'content' => HTML::chars($this->_page->meta_keywords())))
			->add(array('name' => 'description', 'content' => HTML::chars($this->_page->meta_description())))
			->add(array('name' => 'robots', 'content' => HTML::chars($this->_page->robots)))
			->group('content-type', '<meta http-equiv="content-type" content=":content; charset=utf-8" />', array(':content' 
				=> HTML::chars($this->_page->mime())));
	}
	
	/**
	 * Генерация тега meta
	 * Если не передан параметр $group, происходит поиск атрибута [name] и берется
	 * его значение, если и там нет, то `Text::random()`
	 *
	 *		Meta::factory($page)->add(array('name' => 'description', ...));
	 * 
	 * @uses HTML::attributes для обработки атрибутов
	 * 
	 * @param array $attributes массив атрибутов
	 * @param string $group Группа
	 * @return type
	 */
	public function add(array $attributes, $group = NULL)
	{
		$meta = "<meta".HTML::attributes($attributes)." />";
		
		if($group === NULL)
		{
			if(isset($attributes['name']))
			{
				$group = $attributes['name'];
			}
			else
			{
				$group = Text::random();
			}
		}

		return $this->group($group, $meta);
	}
	
	/**
	 * Указание title
	 * 
	 *		Meta::factory($page)->title('New title');
	 * 
	 * @param string $title
	 * @return KodiCMS_Meta
	 */
	public function title( $title )
	{
		return $this->group('title', '<title>:title</title>', array(':title'
				=> HTML::chars($title)));
	}

	/**
	 * Установка файла CSS стиля
	 * 
	 *		Meta::factory($page)->css('bootstrap', PLUGINS_URL . 'test/public/css/bootstrap.min.css');
	 *
	 * @param   string   Asset name.
	 * @param   string   Asset source
	 * @param   mixed    Dependencies
	 * @param   array    Attributes for the <link /> element
	 * @return  KodiCMS_Meta
	 */
	public function css($handle, $src, $deps = NULL, $attrs = NULL)
	{
		Assets::css($handle, $src, $deps, $attrs);
		return $this;
	}
	
	/**
	 * Установка файла JS
	 * 
	 *		Meta::factory($page)->js('bootstrap', PLUGINS_URL . 'test/public/js/bootstrap.min.js', 'jquery');
	 *
	 * @param   mixed    Asset name if `string`, sets `$footer` if boolean
	 * @param   string   Asset source
	 * @param   mixed    Dependencies
	 * @param   bool     Whether to show in header or footer
	 * @return  KodiCMS_Meta
	 */
	public function js($handle, $src, $deps = NULL, $footer = FALSE)
	{
		Assets::js($handle, $src, $deps, $footer);
		return $this;
	}
	
	/**
	 * Добавление произвольного HTML
	 * 
	 *		Meta::factory($page)->group('content-type', '<meta http-equiv="content-type" content=":content; charset=utf-8" />', 
	 *			array(':content' => HTML::chars($this->_page->mime())));
	 * 
	 * @param string $handle
	 * @param string $content
	 * @param array $params
	 * @param string $deps
	 * @return \KodiCMS_Meta
	 */
	public function group($handle, $content, $params = array(), $deps = NULL)
	{
		Assets::group('head', $handle, strtr($content, $params), $deps);
		return $this;
	}

	/**
	 * Генерация HTML кода
	 * 
	 * @return string
	 */
	public function render()
	{
		return Assets::group('head')
				. Assets::css()
				. Assets::js();
	}

	public function __toString()
	{
		return (string) $this->render();
	}
}