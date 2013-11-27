<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_Page_Front {

	/**
	 *
	 * @var string 
	 */
	public $title = '';
	
	/**
	 *
	 * @var string 
	 */
	public $breadcrumb;
	
	/**
	 *
	 * @var string 
	 */
	public $author;
	
	/**
	 *
	 * @var integer 
	 */
	public $author_id;
	
	/**
	 *
	 * @var string 
	 */
	public $updator;
	
	/**
	 *
	 * @var integer 
	 */
	public $updator_id;
	
	/**
	 *
	 * @var string 
	 */
	public $slug = '';
	
	/**
	 *
	 * @var string 
	 */
	public $meta_title = '';
	
	/**
	 *
	 * @var string 
	 */
	public $meta_keywords = '';
	
	/**
	 *
	 * @var string 
	 */
	public $meta_description = '';
	
	/**
	 *
	 * @var string 
	 */
	public $url = '';

	/**
	 *
	 * @var Model_Front_Page 
	 */
	public $parent = NULL;
	
	/**
	 *
	 * @var integer 
	 */
	public $level = NULL;
	
	/**
	 *
	 * @var boolean 
	 */
	public $needs_login;

	/**
	 *
	 * @var array 
	 */
	protected $_tags = NULL;
	
	/**
	 *
	 * @var array 
	 */
	protected $_parts = NULL;
	
	/**
	 *
	 * @var array 
	 */
	protected $_fields = NULL;
	
	/**
	 *
	 * @var array 
	 */
	protected $_parts_data = array();

	/**
	 *
	 * @var array 
	 */
	private static $pages_cache = array();
	
	/**
	 *
	 * @var array 
	 */
	protected $_blocks = array();
	
	/**
	 *
	 * @var Model_File_Layout 
	 */
	protected $_layout_object = NULL;
	
	/**
	 *
	 * @var Behavior_Abstract 
	 */
	protected $_behavior = NULL;

	/**
	 * 
	 * @param string $message
	 * @param array $params
	 * @throws HTTP_Exception_404
	 */
	public static function not_found($message = 'Page not found', $params = NULL)
	{
		Observer::notify('page_not_found', $message, $params);
		throw new HTTP_Exception_404($message, $params);
	}

	/**
	 * 
	 * @param array $object
	 * @param Model_Page_Front $parent
	 */
	public function __construct($object, $parent)
	{
		if($parent instanceof Model_Page_Front)
			$this->parent = $parent;

		foreach ($object as $key => $value) 
		{
			$this->$key = $value;
		}

		if ($this->parent instanceof Model_Page_Front)
		{
			$this->setUrl();
		}

		$this->level = $this->level();
	}

	/**
	 * 
	 * @return \Model_Page_Front
	 */
	protected function setUrl()
	{
		$this->url = trim($this->parent->url .'/'. $this->slug, '/');
		return $this;
	}

	/**
	 * 
	 * @return integer
	 */
	public function id() 
	{ 
		return $this->id; 
	}
	
	/**
	 * 
	 * @return string
	 */
	public function title() 
	{
		return $this->title; 
	}
	
	/**
	 * 
	 * @return string
	 */
	public function meta_title() 
	{
		$title = strtr($this->meta_title, array('\'' => '\\\'', '\\' => '\\\\'));
		$fields = array();
		
		$found = preg_match_all(
			'/(?<!\{)\{('.
				'((\$|\:)[A-Za-z0-9_\-\.\/]+)'. // {$abc}, {:abc}
				'|(\-?[0-9]+(\|[^\'\}]*)?)'. // {1}, {-1| &gt; }
				'|[\.]+'.
			')\}(?!\})/u', $title, $fields);

		if($found) 
		{
			$fields = array_unique($fields[1]);
			$parts = array();
			foreach($fields as $i => $f) 
			{
				$patterns[] = '/(?<!\\{)\\{'.preg_quote($f, '/').'\\}(?!\\})/u';
				$param = NULL;
				switch($f) 
				{
					case '.': // Current page
						$parts[] = $this->title();
					break;
					case '..': // Parent page
						$parent = $this->parent();
						if($parent instanceof Model_Page_Front)
							$parts[] = $this->parent()->meta_title();
					break;
					default: // Level
						if($f >= 0 AND $this->level() != $f)
						{
							$parent = $this->parent($f);
							if($parent instanceof Model_Page_Front)
								$parts[] = $this->parent()->meta_title();
						}
					break;
					
				}
				
				switch($f{0}) 
				{
					case '$':
						$param = substr($f, 1);
					break;
				}
				
				if($param !== NULL)
				{
					$parts[] = Config::get('site', $param);
				}
			}
			
			$title = preg_replace($patterns, $parts, $title);
		}
		
		return $title; 
	}
	
	/**
	 * 
	 * @return string
	 */
	public function breadcrumb() 
	{
		if(empty($this->breadcrumb))
		{
			$this->breadcrumb = $this->title;
		}

		return $this->breadcrumb; 
	}

	public function author() { return $this->author; }
	public function author_id() { return $this->author_id; }
	public function updator() { return $this->updator; }
	public function updator_id() { return $this->updator_id; }
	public function slug() { return $this->slug; }

	/**
	 * 
	 * @param string $default
	 * @return string
	 */
	public function meta_keywords($default = NULL) 
	{
		return !empty($this->meta_keywords) ? $this->meta_keywords : $default; 
	}

	/**
	 * 
	 * @param string $default
	 * @return string
	 */
	public function meta_description($default = NULL) 
	{ 
		return ! empty($this->meta_description) ? $this->meta_description : $default; 
	}

	/**
	 * 
	 * @return string
	 */
	public function url()
	{
		$uri = $this->url;
		if(!URL::check_suffix( $uri, '.' ) AND $uri != '')
		{
			$uri .= URL_SUFFIX;
		}

		return URL::base(TRUE) . $uri; 
	}

	/**
	 * 
	 * @return integer
	 */
	public function level()
	{
		if ($this->level === NULL)
		{
			$this->level = empty($this->url) ? 0 : substr_count($this->url, '/') + 1;
		}

		return $this->level;
	}

	/**
	 * 
	 * @return array
	 */
	public function tags()
	{
		if ( $this->_tags === NULL )
		{
			$this->_tags = $this->_load_tags();
		}

		return $this->_tags;
	}

	/**
	 * 
	 * @return boolean
	 */
	public function is_active()
	{
		return (strpos(Request::current()->url(), $this->url) === 1);
	}

	/**
	 * 
	 * @param string $label
	 * @param array $options
	 * @param boolean $check_current
	 * @return string
	 */
	public function link($label = NULL, $options = array(), $check_current = TRUE)
	{
		if ($label == NULL)
		{
			$label = $this->title();
		}

		if ($check_current === TRUE)
		{
			if ($this->is_active())
			{
				if(!isset($options['class']))
				{
					$options['class'] = '';
				}

				$options['class'] .= ' current';
			}
		}

		return HTML::anchor($this->url(), $label, $options);
	}

	/**
	 * 
	 * @param string $format
	 * @param string $which_one
	 * @return string
	 */
	public function date($format = NULL, $which_one = 'publish')
	{
		if ($which_one == 'update' || $which_one == 'updated')
		{
			return Date::format($this->updated_on, $format);
		}
		else if ($which_one == 'publish' || $which_one == 'published')
		{
			return Date::format($this->published_on, $format);
		}
		else
		{
			return Date::format($this->created_on, $format);
		}
	}
	
	/**
	 * 
	 * @return Behavior_Abstract
	 */
	public function behavior()
	{
		return $this->_behavior;
	}

	/**
	 * 
	 * @param integer $level
	 * @return array
	 */
	public function breadcrumbs($level = 0)
	{
		$crumbs = Breadcrumbs::factory();

		if ( $this->parent instanceof Model_Page_Front 
				AND $this->level > $level)
		{
			$this->parent->_recurse_breadcrumbs($level, $crumbs);
		}

		$crumbs->add($this->breadcrumb(), $this->url, TRUE, NULL, array(
			'id' => $this->id
		));

		return $crumbs;
	}

	private function _recurse_breadcrumbs($level, &$crumbs)
	{
		if ($this->parent instanceof Model_Page_Front 
				AND $this->level > $level)
		{
			$this->parent->_recurse_breadcrumbs($level, $crumbs);
		}
		
		$crumbs->add($this->breadcrumb(), $this->url, FALSE, NULL, array(
			'id' => $this->id
		));
	}
	
	/**
	 * 
	 * @param string $key
	 * @param string $default
	 * @param boolean $inherit
	 * @return string
	 */
	public function field($key, $default = NULL, $inherit = FALSE) 
	{
		if ($this->has_field( $key ))
		{
			return $this->_fields[$key];
			
		}
		else if ($inherit !== FALSE
				AND $this->parent instanceof Model_Page_Front )
		{
			return $this->parent->field($key, $default, $inherit);
		}
		
		return $default;
	}

	/**
	 * 
	 * @param string $key
	 * @param boolean $inherit
	 * @return boolean
	 */
	public function has_field($key, $inherit = FALSE)
	{
		if($this->_fields === NULL)
		{
			$this->_fields = ORM::factory('Page_Field')->get_by_page_id($this->id)->as_array('key', 'value');
		}
		
		if(isset($this->_fields[$key]))
		{
			return TRUE;
		}
		else if($inherit !== FALSE 
				AND $this->parent instanceof Model_Page_Front )
		{
			return $this->parent->has_field($key, $inherit);
		}

		return FALSE;
	}

	/**
	 * 
	 * @param string $part
	 * @param boolean $inherit
	 * @return boolean
	 */
	public function has_content($part, $inherit = FALSE)
	{
		if($this->_parts === NULL)
		{
			$this->_parts = $this->_load_parts();
		}
		
		if(isset($this->_parts[$part]))
		{
			return TRUE;
		}
		else if($inherit !== FALSE 
				AND $this->parent instanceof Model_Page_Front )
		{
			return $this->parent->has_content($part, TRUE);
		}

		return FALSE;
	}

	/**
	 * 
	 * @param string $part
	 * @param boolean $inherit
	 * @param integer $cache_lifetime
	 * @return null
	 */
	public function content($part = 'body', $inherit = FALSE, $cache_lifetime = NULL, array $tags = array())
	{		
		if ($this->has_content( $part ))
		{
			$view = $this->get_part($part);
			
			$cache_lifetime = Arr::path($this->_parts_data, $part . '.cache_lifetime', $cache_lifetime);
			$tags = Arr::path($this->_parts_data, $part . '.tags', $tags);
			
			$tags[] = 'page_parts';
			
			if( $cache_lifetime !== NULL 
				AND ! Fragment::load( $this->id . $part . Request::current()->uri(), (int) $cache_lifetime ))
			{
				echo $view;				

				Fragment::save_with_tags((int) $cache_lifetime, $tags);
			}
			else if($cache_lifetime === NULL)
			{
				echo $view;
			}
			
		}
		else if ($inherit !== FALSE
				AND $this->parent instanceof Model_Page_Front )
		{
			$this->parent->content($part, TRUE, $cache_lifetime);
		}
	}
	
	/**
	 * 
	 * @param string $part
	 * @return View
	 */
	public function get_part( $part )
	{
		$html = NULL;

		if( $this->_parts[$part] instanceof Model_Page_Part )
		{
			$html = View_Front::factory()
				->set('page', $this)
				->render_html($this->_parts[$part]->content_html);
		}
		else if( $this->_parts[$part] instanceof Kohana_View )
		{
			$html = $this->_parts[$part]->render();
		}

		$view = View::factory('system/blocks/part', array(
			'page' => $this,
			'part' => $part,
			'html' => $html,
			'block' => $part
		));
		
		return $view;
	}

	/**
	 * 
	 * @return Model_Page_Front
	 */
	public function previous()
	{
		if( $this->parent instanceof Model_Page_Front )
		{
			$pages = $this->parent->children(array(
				'where' => array(array('page.id', '<', $this->id)),
				'order_by' => array(array('page.created_on', 'desc')),
				'limit' => 1
			));
			
			return isset($pages[0]) ? $pages[0] : NULL;
		}
		
		return NULL;
	}

	/**
	 * 
	 * @return Model_Page_Front
	 */
	public function next()
	{
		if( $this->parent instanceof Model_Page_Front )
		{
			$pages = $this->parent->children(array(
				'where' => array(array('page.id', '<', $this->id)),
				'order_by' => array(array('page.created_on', 'asc')),
				'limit' => 1
			));
			
			return isset($pages[0]) ? $pages[0] : NULL;
		}
		
		return NULL;
	}

	/**
	 * 
	 * @param array $clause
	 * @param array $values
	 * @param boolean $include_hidden
	 * @return array
	 */
	public function children($clause = NULL, $values = array(), $include_hidden = FALSE)
	{
		$page_class = get_called_class();

		if(!isset($clause['order_by']))
		{
			$clause['order_by'] = array(
				array('page.position', 'desc'),
				array('page.id', 'asc')
			);
		}

		$sql = DB::select('page.*')
			->select(array('author.username', 'author'), array('author.id', 'author_id'))
			->select(array('updator.username', 'updator'), array('updator.id', 'updator_id'))
			->from(array(Model_Page::tableName(), 'page'))
			->join(array('users', 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array('users', 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id')
			->where('parent_id', '=', $this->id)
			->where('status_id', 'in', self::_get_statuses($include_hidden));
		
		if(Config::get('page', 'check_date') == Config::YES)
		{
			$sql->where('published_on', '<=', DB::expr('NOW()'));
		}
		
		$sql = $this->filter_by_tags($sql);

		$sql = Record::_conditions($sql, $clause);

		// hack to be able to redefine the page class with behavior
		if ( ! empty( $this->behavior_id ) )
		{
			// will return Page by default (if not found!)
			$page_class = Behavior::load_page($this->behavior_id);
		}

		$query = $sql
			->cache_tags( array('pages') )
			->cached((int) Config::get('cache', 'front_page'))
			->execute();

		$pages = array();
		foreach ($query as $object)
		{
			$pages[] = new $page_class($object, $this);
		}

		return $pages;
	}

	/**
	 * 
	 * @param array $clause
	 * @param array $values
	 * @param boolean $include_hidden
	 * @return integer
	 */
	public function children_count($clause = NULL, $values = array(), $include_hidden = FALSE)
	{
		$page_class = get_called_class();

		if(!isset($clause['order_by']))
		{
			$clause['order_by'] = array(
				array('page.position', 'desc'),
				array('page.id', 'asc')
			);
		}

		$sql = DB::select(array(DB::expr('COUNT(*)'), 'total'))
			->from(array(Model_Page::tableName(), 'page'))
			->where('parent_id', '=', $this->id)
			->where('status_id', 'in', self::_get_statuses($include_hidden));
		
		if(Config::get('page', 'check_date') == Config::YES)
		{
			$sql->where('published_on', '<=', DB::expr('NOW()'));
		}
		
		$sql = $this->filter_by_tags($sql);

		// Prepare SQL
		$sql = Record::_conditions($sql, $clause);

		return (int) $sql
			->cache_tags( array('pages') )
			->cached((int) Config::get('cache', 'front_page'))
			->execute()
			->get('total');
	}
	
	public function filter_by_tags($sql)
	{
		$tags = Context::instance()->get('tag');
		
		if(empty($tags)) return $sql;
		$tags = explode(',', $tags);

		return $sql->join(array(Model_Page_Tag::TABLE_NAME, 'pts'), 'inner')
			->distinct(TRUE)
			->on('pts.page_id', '=', 'page.id')
			->join(array(Model_Tag::TABLE_NAME, 'ts'))
			->on('pts.tag_id', '=', 'ts.id')
			->where('ts.name', 'in', $tags);
	}

	/**
	 * 
	 * @param string $uri
	 * @return string|boolean
	 */
	public static function find_similar($uri)
	{
		if(empty($uri))
		{
			return FALSE;
		}

		$uri_slugs = array_merge(array(''), preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY));

		$config = Kohana::$config->load('similar');
		$statuses = $config->get('find_in_statuses', array());

		$slugs = DB::select('id', 'slug')
			->from(Model_Page::tableName())
			->where('status_id', 'in', $statuses);
		
		if(Config::get('page', 'check_date') == Config::YES)
		{
			$slugs->where('published_on', '<=', DB::expr('NOW()'));
		}
		
		$slugs = $slugs
			->execute()
			->as_array('id', 'slug');

		$new_slugs = array();

		foreach ($uri_slugs as $slug)
		{
			if(in_array($slug, $slugs))
			{
				$new_slugs[] = $slug;
				continue;
			}

			$similar_pages = Text::similar_word($slug, $slugs);

			if(!empty($similar_pages))
			{
				$page_id = key($similar_pages);
				$page = self::findById($page_id);
				$new_slugs[] = $page->slug;
			}
		}

		if(!$config['return_parent_page'] AND (count($uri_slugs) != count($new_slugs)))
		{
			return FALSE;
		}

		$uri = implode('/', $new_slugs);

		$page = self::find($uri);

		return $page ? $uri : FALSE;
	}

	/**
	 * 
	 * @param string $slug
	 * @param integer $parent
	 * @return boolean|\Model_Page_Front
	 */
	public static function findBySlug( $slug, $parent )
	{
		$page_cache_id = self::_get_cache_id( $slug, $parent );

		if( isset(self::$pages_cache[ $page_cache_id ]) )
		{
			return self::$pages_cache[ $page_cache_id ];
		}

		$page_class = get_called_class();

		$parent_id = $parent instanceof Model_Page_Front ? $parent->id : 0;

		$page = DB::select('page.*')
			->select(array('author.username', 'author'))
			->select(array('updator.username', 'updator'))
			->from(array(Model_Page::tableName(), 'page'))
			->join(array('users', 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array('users', 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id')
			->where('slug', '=', $slug)
			->where('parent_id', '=', $parent_id)
			->where('status_id', 'in', self::_get_statuses(TRUE))
			->limit(1)
			->cache_tags( array('pages') )
			->cached((int)Config::get('cache', 'front_page'))
			->as_object();
		
		if(Config::get('page', 'check_date') == Config::YES)
		{
			$page->where('published_on', '<=', DB::expr('NOW()'));
		}
		
		$page = $page
			->execute()
			->current();

		if( ! $page ) return FALSE;

		// hook to be able to redefine the page class with behavior
		if ( !empty( $parent->behavior_id ) )
		{
			// will return Page by default (if not found!)
			$page_class = Behavior::load_page($parent->behavior_id);
		}		

		// create the object page
		$page = new $page_class($page, $parent);

		$pages_cache[ $page_cache_id ] = $page;

		return $page;
	}

	/**
	 * 
	 * @param string $uri
	 * @return \Model_Page_Front
	 */
	public static function find( $uri )
	{
		$uri = trim($uri, '/');

		// adding the home SYSPATH
		$urls = array_merge(array(''), preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY));
		$url = '';

		$page = new stdClass;
		$page->id = 0;

		$parent = NULL;

		foreach ($urls as $page_slug)
		{
			$url = ltrim($url . '/' . $page_slug, '/');

			if( $page = self::findBySlug($page_slug, $parent) )
			{
				// check for behavior
				if( !empty( $page->behavior_id ) )
				{
					$page->_behavior = Behavior::load($page->behavior_id, $page, $url, $uri);

					return $page;
				}
			}
			else
				break;

			$parent = $page;
		}
		return $page;
	}

	/**
	 * 
	 * @param integer $id
	 * @return \Model_Page_Front|boolean
	 */
	public static function findById( $id )
	{
		$page_class = get_called_class();

		$page = DB::select('page.*')
			->select(array('author.username', 'author'))
			->select(array('updator.username', 'updator'))
			->from(array(Model_Page::tableName(), 'page'))
			->join(array('users', 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array('users', 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id')
			->where('page.id', '=', $id)
			->where('published_on', '<=', DB::expr('NOW()'))
			->where('status_id', 'in', self::_get_statuses(TRUE))
			->limit(1)
			->cache_tags( array('pages') )
			->cached((int)Config::get('cache', 'front_page'))
			->as_object();
		
		if(Config::get('page', 'check_date') == Config::YES)
		{
			$page->where('published_on', '<=', DB::expr('NOW()'));
		}
		
		$page = $page
			->execute()
			->current();

		if( ! $page ) return FALSE;

		if ($page->parent_id)
		{
			$parent = self::findById($page->parent_id);
		}
		else
		{
			$parent = NULL;
		}

		// hook to be able to redefine the page class with behavior
		if ( !empty($parent->behavior_id) )
		{
			// will return Page by default (if not found!)
			$page_class = Behavior::load_page($parent->behavior_id);
		}

		// create the object page
		$page = new $page_class($page, $parent);

		return $page;
	}

	/**
	 * 
	 * @param integer $level
	 * @return boolean|\Model_Page_Front
	 */
	public function parent($level = NULL)
	{
		if ($level === NULL)
		{
			return $this->parent;
		}

		if ($level > $this->level)
		{
			return NULL;
		}
		else if ($this->level == $level)
		{
			return $this;
		}
		else if($this->parent instanceof Model_Page_Front)
		{
			return $this->parent->parent($level);
		}

		return NULL;
	}
	
	/**
	 * 
	 * @return Model_File_Layout
	 * @throws Kohana_Exception
	 */
	public function get_layout_object()
	{
		if($this->_layout_object === NULL)
		{
			$layout_name = $this->layout();
			$this->_layout_object = new Model_File_Layout($layout_name);
		}
		
		if( ! $this->_layout_object->is_exists())
		{
			throw new  Kohana_Exception('Layout file :file not found!', array(
				':file' => $layout_name
			));
		}
		
		return $this->_layout_object;
	}

	/**
	 * 
	 * @return View_Front
	 * @throws Kohana_Exception
	 */
	public function render_layout()
	{
		$layout = $this->get_layout_object();
		return View_Front::factory($layout->get_file())
			->set('page', $this);
	}
	
	/**
	 * Mime type
	 * @return string
	 */
	public function mime()
	{
		$mime = File::mime_by_ext(pathinfo($this->url(), PATHINFO_EXTENSION));

		return $mime === FALSE ? 'text/html' : $mime;
	}

	/**
	 * find the layoutId of the page where the layout is set
	 */	
	public function layout()
	{
		if ( ! empty($this->layout_file) )
		{
			return $this->layout_file;
		}
		else if( $this->parent instanceof Model_Page_Front )
		{
			return $this->parent->layout();
		}

		return NULL;
	}

	/**
	 * Finds the "login needed" status for the page.
	 *
	 * @return int Integer corresponding to one of the LOGIN_* constants.
	 */
	public function needs_login()
	{
		if ($this->needs_login == Model_Page::LOGIN_INHERIT 
				AND $this->parent instanceof Model_Page_Front)
		{
			return $this->parent->needs_login();
		}

		return $this->needs_login;
	}
	
	/**
	 * 
	 * @param string $part
	 * @param Model_Page_Part $data
	 * @return \Model_Page_Front
	 */
	public function set_part($part, $data, $cache_lifetime = NULL, array $tags = array())
	{
		$this->_parts[$part] = $data;
		$this->_parts_data[$part] = array(
			'cache_lifetime' => $cache_lifetime,
			'tags' => $tags
		);
		
		return $this;
	}

	/**
	 * TODO вынести в класс Model_Page_Part
	 * @return array
	 */
	final private function _load_parts()
	{
		return DB::select('name', 'content', 'content_html')
			->from(Model_Page_Part::tableName())
			->where('page_id', '=', $this->id)
			->cache_tags( array('page_parts') )
			->as_object('Model_Page_Part')
			->cached((int)Config::get('cache', 'page_parts'))
			->execute()
			->as_array('name');	
	}

	/**
	 * @return array
	 */
	final private function _load_tags()
	{
		return Model_Page_Tag::find_by_page($this->id);
	}


	/**
	 * 
	 * @param boolean $include_hidden
	 * @return array
	 */
	final protected static function _get_statuses($include_hidden = FALSE)
	{
		$statuses = array(Model_Page::STATUS_REVIEWED, Model_Page::STATUS_PUBLISHED);
		
		if($include_hidden)
		{
			$statuses[] = Model_Page::STATUS_HIDDEN;
		}

		return $statuses;
	}
	
	/**
	 * 
	 * @param array|string $slug
	 * @param Model_Page_Front $parent
	 * @return string
	 */
	final protected static function _get_cache_id($slug, $parent)
	{
		if(  is_array( $slug ))
		{
			$slug = implode('::', $slug);
		}
		
		if($parent instanceof Model_Page_Front)
		{
			$parent = $parent->id;
		}
		else
		{
			$parent = 0;
		}
		
		return $slug . $parent;
	}
}