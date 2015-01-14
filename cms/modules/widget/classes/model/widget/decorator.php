<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class Model_Widget_Decorator {
	
	const ORDER_ASC = 'ASC';
	const ORDER_DESC = 'DESC';

	/**
	 * Идентификатор виджета
	 * @var integer
	 */
	public $id;
	
	/**
	 * Название виджета
	 * @var string 
	 */
	public $name;

	/**
	 * Описание виджета
	 * @var string 
	 */
	public $description = '';
	
	/**
	 * Заголовок в шаблоне
	 * @var string 
	 */
	public $header = NULL;

	/**
	 * Файл шаблона
	 * @var string
	 */
	public $template = NULL;
	
	/**
	 * Файл шаблона настроек виджета
	 * @var string 
	 */
	public $backend_template = NULL;
	
	/**
	 * Файл шаблона вывода
	 * @var string 
	 */
	public $frontend_template = NULL;
	
	/**
	 * Параметры передаваемые в шаблон
	 * @var array
	 */
	public $template_params = array();

	/**
	 * Название блока, в который помещен виджет
	 * @var string
	 */
	public $block = NULL;
	
	/**
	 * Позиция виджета в блоке
	 * @var integer
	 */
	public $position = 500;
	
	/**
	 * Виджет влияет на хлебные крошки
	 * @var boolean 
	 */
	public $crumbs = FALSE;

	/**
	 * Включение / Отключение кеширования
	 * @var bool 
	 */
	public $caching = FALSE;
	
	/**
	 * Время хранения кеша (По умолчанию месяц)
	 * @var integer 
	 */
	public $cache_lifetime = Date::MONTH;
	
	/**
	 * Теги кеширования
	 * @var array 
	 */
	public $cache_tags = array();
	
	/**
	 * Роли, с которыми видно виджет. 
	 * Если не указаны, то видно всем
	 * 
	 * @var array 
	 */
	public $roles = array();
	
	/**
	 * Подключаемые медиа файлы для текущего виджета
	 * 
	 * Для подключения файлов в шаблон используется класс Assets
	 * 
	 * @var array 
	 */
	public $media = array();
	
	/**
	 * Подключаемые медиа пакеты для текущего виджета
	 * Для подключения пакетов в шаблон используется класс Assets
	 * 
	 * @var array 
	 */
	public $media_packages = array();

	/**
	 * Генерировать ошибку 404
	 * @var bool 
	 */
	public $throw_404 = FALSE;
	
	/**
	 * 
	 * @var string 
	 */
	public $frontend_template_preffix = 'frontend';

	/**
	 *
	 * @var Context 
	 */
	protected $_ctx = NULL;

	/**
	 * Тип виджета
	 * @var string 
	 */
	protected $_type;
	
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
	 * Дополнительные параметры виджета
	 * @var array 
	 */
	protected $_data = array();
	
	public function __construct()
	{
		$this->_ctx = Context::instance();
		$this->_set_type();
	}
	
	/**
	 * 
	 * @return string
	 */
	public function type($as_key = TRUE)
	{
		if ($as_key === TRUE)
		{
			return $this->_type;
		}

		$widget_types = Widget_Manager::map();

		$type = $this->_type;

		foreach ($widget_types as $group => $types)
		{
			if (isset($types[$type]))
			{
				$type = $types[$type];
			}
		}

		return $type;
	}

	/**
	 * Сеттер доплнительных параметров
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @return \Model_Widget_Decorator
	 */
	public function set($name, $value)
	{
		$this->_data[$name] = $value;
		
		return $this;
	}

	/**
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @return \Model_Widget_Decorator
	 */
	public function bind($name, & $value)
	{
		$this->_data[$name] = & $value;
		return $this;
	}

	/**
	 * Геттер дополнительных параметров
	 * 
	 * @param string $name
	 * @param mixed $default
	 * @return mided
	 */
	public function & get($name, $default = NULL)
	{
		$result = $default;
		if (array_key_exists($name, $this->_data))
		{
			$result = $this->_data[$name];
		}

		return $result;
	}

	/**
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public function & __get($name)
	{
		return $this->get($name);
	}

	/**
	 * 
	 * @return boolean
	 */
	public function is_handler()
	{
		return $this->_is_handler;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function use_template()
	{
		return $this->_use_template;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function use_caching()
	{
		return $this->_use_caching;
	}

	/**
	 * Получения пути до шаблона настроек виджета.
	 * Если шаблон не указан в параметре {@see Model_Widget_Decorator::backend_template},
	 * то он основывается на типе виджета
	 * 
	 * @return string
	 */
	public function backend_template()
	{
		if ($this->backend_template === NULL)
		{
			$this->backend_template = $this->type();
		}

		return $this->backend_template;
	}
	
	/**
	 * Получения пути до шаблона для Frontend.
	 * Если шаблон не указан в параметре {@see Model_Widget_Decorator::frontend_template},
	 * то он основывается на типе виджета
	 * 
	 * @return string
	 */
	public function frontend_template()
	{
		if ($this->frontend_template === NULL)
		{
			$this->frontend_template = $this->type();
		}

		return $this->frontend_template;
	}
	
	/**
	 * Шаблон по умолчанию.
	 * 
	 * @return string
	 */
	public function default_template()
	{
		if (($template = Kohana::find_file('views', 'widgets/' . $this->frontend_template_preffix . '/' . $this->frontend_template())) === FALSE)
		{
			$template = Kohana::find_file('views', 'widgets/' . $this->frontend_template_preffix . '/default');
		}

		return $template;
	}

	/**
	 * Установка настроек кеша
	 * 
	 * @param array $data array(['caching'] => [BOOLEAN], 'cache_lifetime' => [INTEGER], 'cache_tags' => [ARRAY])
	 * @return \Model_Widget_Decorator
	 */
	public function set_cache_settings(array & $data)
	{
		$this->caching = (bool) Arr::get($data, 'caching', FALSE);
		$this->cache_lifetime = (int) Arr::get($data, 'cache_lifetime');

		$this->cache_tags = explode(',', Arr::get($data, 'cache_tags'));

		unset($data['caching'], $data['cache_lifetime'], $data['cache_tags']);

		return $this;
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
	
	
	/**
	 * Получение списка тегов кеширования в виде строки
	 * 
	 * @return string
	 */
	public function cache_tags()
	{
		return implode(', ', (array) $this->cache_tags);
	}

	/**
	 * Метод очистки кеша виджета.
	 * 
	 * @todo Не совсем корректно работает с динамическим кешем
	 * @return \Model_Widget_Decorator
	 */
	public function clear_cache()
	{
		if ($this->caching === TRUE)
		{
			Fragment::delete($this->get_cache_id());
		}

		return $this;
	}
	
	/**
	 * Метод очистки кеша по тегам
	 * @return \Model_Widget_Decorator
	 */
	public function clear_cache_by_tags()
	{
		if (!empty($this->cache_tags) AND Kohana::$caching === TRUE)
		{
			$cache = Cache::instance();

			if ($cache instanceof Cache_Tagging)
			{
				if (is_array($this->cache_tags))
				{
					foreach ($this->cache_tags as $tag)
					{
						$cache->delete_tag($tag);
					}
				}
				else
				{
					$cache->delete_tag($this->cache_tags);
				}
			}
		}

		return $this;
	}
	
	/**
	 * Получения строки типа виджета и ID
	 * Используется для создания нового типа почтового уведомления
	 * 
	 * @return string
	 */
	public function get_hash()
	{
		return 'widget_' . $this->type() . '_' . $this->id;
	}
	
	/**
	 * Связывание почтового уведомления вместе с виджетом
	 * 
	 * @param array $fields
	 * @return Model_Email_Type
	 */
	public function create_email_type(array $fields)
	{
		$email_type = ORM::factory('email_type', array('code' => $this->get_hash()));

		if (!$email_type->loaded())
		{
			$email_type->values(array(
				'code' => $this->get_hash(),
				'name' => $this->name
			))->create();
		}

		if (!empty($fields))
		{
			$email_type->set('data', $fields)->update();
		}

		return $email_type;
	}
	
	/**
	 * Запуск почтового уведомления
	 * 
	 * @return boolean
	 */
	public function handle_email_type(array $values)
	{
		return Email_Type::get($this->get_hash())->send($values);
	}
	
	/**
	 * Проверка виджета на существование в БД
	 * 
	 * @return bool
	 */
	public function loaded()
	{
		return isset($this->id) AND $this->id > 0;
	}
	
	/**
	 * Метод используется для поиска и подключения шаблона настроек для админ 
	 * интерфейса.
	 * Если шаблон настроек не найдет, то выводятся стандартные настройки для виджета
	 * 
	 * @return View|null
	 */
	public function fetch_backend_content()
	{
		try
		{
			$content = View::factory('widgets/backend/' . $this->backend_template(), array(
				'widget' => $this
			))->set($this->backend_data());
		}
		catch (Kohana_Exception $e)
		{
			$content = NULL;
		}
		
		return $content;
	}
	
	/**
	 * Параметры, которые передаются в шаблон настроек виджета
	 * 
	 * @see Model_Widget_Decorator::fetch_backend_content()
	 * @return array
	 */
	public function backend_data()
	{
		return array();
	}
	
	/**
	 * Метод используется для сохранения настроек виджета. 
	 * Вызывается в момент сохранения виджета.
	 * 
	 * @param array $data
	 */
	public function set_values(array $data)
	{
		if (empty($data['roles']))
		{
			$data['roles'] = array();
		}

		if (empty($data['media']))
		{
			$data['media'] = array();
		}

		if (empty($data['media_packages']))
		{
			$data['media_packages'] = array();
		}

		foreach ($data as $key => $value)
		{
			if (method_exists($this, 'set_' . $key))
			{
				$this->{$key} = $this->{'set_' . $key}($value);
			}
			else
			{
				$this->{$key} = $value;
			}
		}

		return $this;
	}
	
	/**
	 * Передача дополнительных парамтеров в виджет
	 * @param array $params Дополнительные параметры
	 * @return \Model_Widget_Decorator
	 */
	public function set_params(array $params = array()) 
	{
		$this->template_params = Arr::merge($params, $this->template_params);

		return $this;
	}

	/**
	 * 
	 * @param array $files
	 * @return array
	 */
	public function set_media($files)
	{
		foreach ($files as $i => $link)
		{
			if (strpos($link, '.css') === FALSE AND strpos($link, '.js') === FALSE)
			{
				unset($files[$i]);
			}

			if (!Valid::url($link))
			{
				$files[$i] = URL::site($link, TRUE);
			}
		}

		return array_unique($files);
	}

	/**
	 * Рендер виджета
	 * @param array $params Дополнительные параметры
	 */
	public function run(array $params = array()) 
	{
		return $this->render($params);
	}

	/**
	 * Рендер виджета во Frontend
	 * 
	 * Отключение комментариев для блока
	 * 
	 *		Block::run('block_name', array('comments' => FALSE));
	 * 
	 * Отключение кеширования виджетов в блоке
	 * 
	 *		Block::run('block_name', array('caching' => FALSE));
	 * 
	 * @param array $params Дополнительные параметры
	 */
	public function render(array $params = array())
	{
		// Проверка прав на видимость виджета
		if (!empty($this->roles))
		{
			if (Auth::is_logged_in())
			{
				if (!Auth::has_permissions($this->roles, FALSE))
				{
					return;
				}
			}
			else
			{
				return;
			}
		}

		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Widget render', $this->name);
		}

		$this->_fetch_template();
		$this->set_params($params);

		$allow_omments = (bool) Arr::get($this->template_params, 'comments', TRUE);
		$caching = (bool) Arr::get($this->template_params, 'caching', $this->caching);

		if ($this->block == 'PRE' OR $this->block == 'POST')
		{
			$allow_omments = FALSE;
		}

		if (Kohana::$caching === FALSE OR $caching === FALSE)
		{
			$this->caching = FALSE;
		}

		if (Arr::get($this->template_params, 'return') === TRUE)
		{
			return $this->_fetch_render();
		}

		if ($allow_omments)
		{
			echo "<!--{Widget: {$this->name}}-->";
		}

		if (
			$this->caching === TRUE
			AND 
			! Fragment::load($this->get_cache_id(), $this->cache_lifetime, TRUE)
		)
		{
			echo $this->_fetch_render();
			Fragment::save_with_tags($this->cache_lifetime, $this->cache_tags);
		}
		else if (!$this->caching)
		{
			echo $this->_fetch_render();
		}

		if ($allow_omments)
		{
			echo "<!--{/Widget: {$this->name}}-->";
		}

		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}
	}
	
	/**
	 * Получение пути до файла шаблона виджета.
	 * Сначала происходит поиск файла по названию Сниппета, если он не 
	 * найден, то просходит поиск шаблона по умолчанию для виджета.
	 * 
	 * @return string
	 */
	protected function _fetch_template()
	{
		if (empty($this->template))
		{
			$this->template = $this->default_template();
		}
		else
		{
			$snippet = new Model_File_Snippet($this->template);

			if ($snippet->is_exists())
			{
				$this->template = $snippet->get_file();
			}
			else if (($this->template = $snippet->find_file()) === FALSE)
			{
				$this->template = $this->default_template();
			}
		}

		return $this->template;
	}

	/**
	 * Подготовка к рендеру виджета
	 * Происходит инициализация View и передача в него переменных из метода
	 * {@see Model_Widget_Decorator::fetch_data()}, а также дополнительных параметров,
	 * переданных в блок шаблона страницы
	 * 
	 * @param array $params
	 * @return View
	 */
	protected function _fetch_render()
	{
		$data = $this->fetch_data();
		$data['params'] = $this->template_params;
		$data['widget_id'] = $this->id;
		
		return View_Front::factory($this->template, $data)
			->bind('header', $this->header);
	}
	
	/**
	 * Установка типа виджета
	 * 
	 * @return \Model_Widget_Decorator
	 */
	protected function _set_type()
	{
		$class_name = get_called_class();
		$this->_type = strtolower(substr($class_name, 13));
		
		return $this;
	}

	/**
	 * Функция запоскается через обсервер frontpage_found
	 * 
	 * @see Context::init_widgets()
	 */
	public function on_page_load() 
	{
		if (!empty($this->media))
		{
			foreach ($this->media as $link)
			{
				if (strpos($link, '.css') !== FALSE)
				{
					Assets::css($link, $link);
				}
				else if (strpos($link, '.js') !== FALSE)
				{
					Assets::js($link, $link);
				}
			}
		}

		if (!empty($this->media_packages))
		{
			Assets::package($this->media_packages);
		}
	}
	
	/**
	 * Функция запоскается через обсервер frontpage_render
	 * 
	 * @see Context::init_widgets()
	 */
	public function after_page_load() {}
	
	/**
	 * Метод изменения хлебных крошек страницы. Вызывается в момент передачи
	 * виджетов на страницу сайта.
	 *  
	 * @see Context::build_crumbs()
	 * @param Breadcrumbs $crumbs
	 */
	public function change_crumbs( Breadcrumbs &$crumbs) {}
	
	/**
	 * Метод используется для передачи списска параметров в шаблон вывода
	 * виджета во Frontend
	 * 
	 *		public function fetch_data()
	 *		{
	 *			....
	 * 
	 *			return array(
	 *				'data' => 'test',
	 *				'param' => 'value'
	 *			);
	 *		}
	 * 
	 * 
	 *		//Frontend snippet template
	 *		echo $data;  // return test
	 *		echo $param;  // return value
	 * 
	 * @return array array([KEY] => [VALUE], ....)
	 */
	abstract public function fetch_data();

	/**
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->render();
	}
	
	/**
	 * Параметры, которые не должны сериализоваться при сохранении объекта
	 * @return array
	 */
	protected function _serialize_vars()
	{
		$vars = get_object_vars($this);
		
		unset(
			$vars['_ctx'],
			$vars['_type'],
			$vars['template'],
			$vars['name'], 
			$vars['description'],
			$vars['backend_template'],
			$vars['frontend_template'],
			$vars['frontend_template_preffix'],
			$vars['_use_template'],
			$vars['_use_caching'],
			$vars['_is_handler'],
			$vars['block'],
			$vars['position'],
			$vars['template_params']
		);
		
		return $vars;
	}
	
	/**
	 * Метод вызывается в момент сохранения объекта в БД
	 * 
	 * @return array
	 */
	public function __sleep()
	{
		return array_keys($this->_serialize_vars());
	}
	
	/**
	 * Метод вызывается в момент загрузки объекта из БД
	 * 
	 * @return array
	 */
	public function __wakeup()
	{
		$this->_ctx = Context::instance();
		$this->_set_type();
	}
}