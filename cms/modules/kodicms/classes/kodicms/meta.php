<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Assets
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Meta {
	
	/**
	 * Фабрика создания объекта Meta информации для шаблона
	 * 
	 *		echo Meta::factory($page)
	 *			->add(array('name' => 'author', 'content' => 'KodiCMS'))
	 *			->js('jquery', 'htpp://code.jquery.com/jquery-1.11.0.min.js');
	 * 
	 * @param Model_Page_Front $page
	 * @return \self
	 */
	public static function factory(Model_Page_Front $page = NULL)
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
	 * При передачи объекта страницы в нем генерируется
	 * 
	 *		<title>...</title>
	 *		<meta name="keywords" content="" />
	 *		<meta name="description" content="" />
	 *		<meta name="robots" content="" />
	 *		<meta name="robots" content="" />
	 *		<meta charset="utf-8">
	 * 
	 * Для переопеределения данных используйте
	 * 
	 *		Meta::factory($page)->add(array('name' => 'description', ...));
	 * 
	 * @param Model_Page_Front $page
	 */
	public function __construct(Model_Page_Front $page = NULL)
	{
		if ($page !== NULL)
		{
			$this->set_page($page, TRUE);
		}
	}

	/**
	 * 
	 * 
	 * @param Model_Page_Front $page
	 * @param boolean $set_page_data Установка данных страницы
	 * @return KodiCMS_Meta
	 */
	public function set_page(Model_Page_Front $page, $set_page_data = FALSE)
	{
		$this->_page = $page;

		if ($set_page_data !== FALSE)
		{
			$this
				->title(HTML::chars($this->_page->meta_title()))
				->add(array('name' => 'keywords', 'content' => HTML::chars($this->_page->meta_keywords())))
				->add(array('name' => 'description', 'content' => HTML::chars($this->_page->meta_description())))
				->add(array('name' => 'robots', 'content' => HTML::chars($this->_page->robots)))
				->add(array('charset' => 'utf-8'), 'meta::charset');
		}

		return $this;
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
	 * @return KodiCMS_Meta
	 */
	public function add(array $attributes, $group = NULL)
	{
		$meta = "<meta" . HTML::attributes($attributes) . " />";

		if ($group === NULL)
		{
			if (isset($attributes['name']))
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
	public function title($title)
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
	 * 
	 * @param string|NULL $handle
	 * @return \KodiCMS_Meta
	 */
	public function remove_css($handle = NULL)
	{
		Assets::remove_css($handle);
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
	 * 
	 * @param string|NULL $handle
	 * @return \KodiCMS_Meta
	 */
	public function remove_js($handle = NULL)
	{
		Assets::remove_js($handle);
		return $this;
	}
	
	/**
	 * Указание favicon 
	 * 
	 * @param string $url
	 * @param string $rel
	 * @return  KodiCMS_Meta
	 */
	public function icon($url, $rel = 'shortcut icon')
	{
		return $this->group('icon', '<link rel=":rel" href=":url" type="image/x-icon" />', array(
			':url' => HTML::chars($url), 
			':rel' => HTML::chars($rel)
		));
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
		Assets::group('FRONTEND', $handle, strtr($content, $params), $deps);
		return $this;
	}
	

	/**
	 * 
	 * @param string|NULL $handle
	 * @return \KodiCMS_Meta
	 */
	public function remove_group($handle = NULL)
	{
		Assets::remove_group('FRONTEND', $handle);
		return $this;
	}
	
	/**
	 * Добавление пакетов
	 * 
	 *		Meta::factory($page)
	 *			->package('jquery-ui')
	 *			->package(array('jquery-ui', 'fancybox'));
	 * 
	 * @param string $handle
	 * @param string $content
	 * @param array $params
	 * @param string $deps
	 * @return \KodiCMS_Meta
	 */
	public function package($name, $footer = FALSE)
	{
		Assets::package($name, $footer);
		return $this;
	}
	
	/**
	 * 
	 * @param boolean $js_footer
	 * @return \KodiCMS_Meta
	 */
	public function minify($js_footer = FALSE)
	{
		list($css_cache, $js_cache) = Assets::minify();
		
		if (!empty($css_cache))
		{
			$this->css('cahce', $css_cache);
		}
		
		if (!empty($js_cache))
		{
			$this->js('cahce', $js_cache, NULL, $js_footer);
		}

		return $this;
	}

	/**
	 * Генерация HTML кода CSS, JS, Meta
	 * 
	 * По умолчанию выводятся все группы CSS, JS, Meta, если вы выводите JS
	 * код перед тегом </body>, то непобхимо передать параметр FALSE и сделать
	 * вывод JS кода в нужно месте с помощью
	 *		
	 *		<?php echo Assets::js(); ?>
	 * 
	 * @param boolean $include_js Включить в вывод JavaScript
	 * @return string
	 */
	public function render($js_footer = FALSE)
	{
		$html = Assets::group('FRONTEND')
				. Assets::css()
				. Assets::js($js_footer);

		return $html;
	}

	public function __toString()
	{
		return (string) $this->render();
	}
}