<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Model
 */
class Model_Page_Front
{

	public $title = '';
	public $breadcrumb;
	public $author;
	public $author_id;
	public $updator;
	public $updator_id;
	public $slug = '';
	public $keywords = '';
	public $description = '';
	public $url = '';

	public $parent = FALSE;
	public $level = FALSE;
	public $tags = FALSE;

	public $needs_login;

	private static $pages_cache = array();

	public static function not_found($message = 'Page not found', $params = NULL)
	{
		Observer::notify('page_not_found', $message, $params);
		throw new HTTP_Exception_404($message, $params);
	}

	public function __construct($object, $parent)
	{
		$this->parent = $parent;

		foreach ($object as $key => $value) 
		{
			$this->$key = $value;
		}

		if ($this->parent)
		{
			$this->setUrl();
		}

		$this->level = $this->level();
		$this->_load_parts();
	}

	protected function setUrl()
	{
		$this->url = trim($this->parent->url .'/'. $this->slug, '/');
	}

	public function id() { return $this->id; }
	public function title() { return $this->title; }
	public function breadcrumb() { return $this->breadcrumb; }
	public function author() { return $this->author; }
	public function author_id() { return $this->author_id; }
	public function updator() { return $this->updator; }
	public function updator_id() { return $this->updator_id; }
	public function slug() { return $this->slug; }

	public function keywords($default = NULL) 
	{
		return !empty($this->keywords) ? $this->keywords : $default; 
	}

	public function description($default = NULL) 
	{ 
		return !empty($this->description) ? $this->description : $default; 
	}

	public function url()
	{
		$uri = $this->url;
		if(!URL::check_suffix( $uri, '.' ) AND $uri != '')
		{
			$uri .= URL_SUFFIX;
		}

		return URL::base(TRUE) . $uri; 

	}

	public function level()
	{
		if ($this->level === FALSE)
		{
			$this->level = empty($this->url) ? 0 : substr_count($this->url, '/') + 1;
		}

		return $this->level;
	}

	public function tags()
	{
		if ( ! $this->tags)
		{
			$this->tags = $this->_load_tags();
		}

		return $this->tags;
	}

	public function is_active()
	{
		return (strpos(Request::current()->url(), $this->url) === 1);
	}

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

	public function breadcrumbs($level = 0)
	{
		$pages = array();

		if ($this->parent && $this->level > $level)
		{
			$pages = Arr::merge($this->parent->_recurse_breadcrumbs($level), $pages);
		}

		$pages[] = $this->breadcrumb;

		return $pages;
	}

	private function _recurse_breadcrumbs($level)
	{
		$pages[] = $this->link($this->breadcrumb, array(), FALSE);

		if ($this->parent && $this->level > $level)
		{
			$pages = Arr::merge($this->parent->_recurse_breadcrumbs($level), $pages);
		}

		return $pages;
	}

	public function has_content($part, $inherit = FALSE)
	{
		if (isset($this->part->{$part}))
		{
			return TRUE;
		}
		else if ($inherit AND $this->parent)
		{
			return $this->parent->has_content($part, TRUE);
		}

		return FALSE;
	}

	public function content($part = 'body', $inherit = FALSE, $cache_lifetime = NULL)
	{		
		if (isset($this->part->{$part}))
		{
			echo View::factory('system/blocks/part')
				->set('page', $this)
				->set('part', $this->part->{$part})
				->set('html', View_Front::factory()
						->set('page', $this)
						->render_html($this->part->{$part}->content_html)
				);
			
		}
		else if ($inherit AND $this->parent)
		{
			return $this->parent->content($part, TRUE, $cache_lifetime);
		}

		return NULL;
	}

	public function previous()
	{
		if ($this->parent)
		{
			return $this->parent->children(array(
				'where' => array(array('page.id', '<', $this->id)),
				'order_by' => array(array('page.created_on', 'desc')),
				'limit' => 1
			));
		}
	}

	public function next()
	{
		if ($this->parent)
		{
			return $this->parent->children(array(
				'where' => array(array('page.id', '<', $this->id)),
				'order_by' => array(array('page.created_on', 'asc')),
				'limit' => 1
			));
		}
	}

	public function children($clause = NULL, $values = array(), $include_hidden = FALSE)
	{
		$page_class = __CLASS__;

		if(!isset($clause['order_by']))
		{
			$clause['order_by'] = array(
				array('page.position', 'desc'),
				array('page.id', 'asc')
			);
		}

		$statuses = array(Model_Page::STATUS_REVIEWED, Model_Page::STATUS_PUBLISHED);
		if($include_hidden !== FALSE)
		{
			$statuses[] = Model_Page::STATUS_HIDDEN;
		}

		$sql = DB::select('page.*')
			->select(array('author.name', 'author'), array('author.id', 'author_id'))
			->select(array('updator.name', 'updator'), array('updator.id', 'updator_id'))
			->from(array(Model_Page::tableName(), 'page'))
			->join(array(User::tableName(), 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array(User::tableName(), 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id')
			->where('parent_id', '=', $this->id)
			->where('published_on', '<=', DB::expr('NOW()'))
			->where('status_id', 'in', $statuses);

		$sql = Record::_conditions($sql, $clause);

		$pages = array();

		// hack to be able to redefine the page class with behavior
		if ( !empty( $this->behavior_id ) )
		{
			// will return Page by default (if not found!)
			$page_class = Behavior::load_page($this->behavior_id);
		}

		$query = $sql
			->cache_tags( array('pages') )
			->cached((int) Kohana::$config->load('global.cache.front_page'))
			->execute();

		// Run!
		if ($query)
		{
			foreach ($query as $object)
			{
				$page = new $page_class($object, $this);
				$pages[] = $page;
			}
		}

		if (Arr::get($clause, 'limit', 0) == 1)
		{
			return isset($pages[0]) ? $pages[0]: FALSE;
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
		$page_class = __CLASS__;

		if(!isset($clause['order_by']))
		{
			$clause['order_by'] = array(
				array('page.position', 'desc'),
				array('page.id', 'asc')
			);
		}

		$statuses = array(Model_Page::STATUS_REVIEWED, Model_Page::STATUS_PUBLISHED);
		if($include_hidden)
		{
			$statuses[] = Model_Page::STATUS_HIDDEN;
		}

		$sql = DB::select(array(DB::expr('COUNT(*)'), 'total'))
			->from(array(Model_Page::tableName(), 'page'))
			->where('published_on', '<=', DB::expr('NOW()'))
			->where('status_id', 'in', $statuses);

		// Prepare SQL
		$sql = Record::_conditions($sql, $clause);

		return (int) $sql
			->cache_tags( array('pages') )
			->cached((int)Kohana::$config->load('global.cache.front_page'))
			->execute()
			->get('total');
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
			->where('status_id', 'in', $statuses)
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
		$page_cache_id = (is_array($slug) ? join($slug) : $slug) . (isset($parent->id) ? $parent->id : 0);

		if( !isset(self::$pages_cache[ $page_cache_id ]) )
		{
			$page_class = __CLASS__;

			$page = FALSE;

			if (is_object($page) && ($page instanceof $page_class))
			{
				return $page;
			}
			else
			{
				$parent_id = $parent ? $parent->id: 0;
				$statuses = array(Model_Page::STATUS_REVIEWED, Model_Page::STATUS_PUBLISHED, Model_Page::STATUS_HIDDEN);

				$page = DB::select('page.*')
					->select(array('author.name', 'author'))
					->select(array('updator.name', 'updator'))
					->from(array(Model_Page::tableName(), 'page'))
					->join(array(User::tableName(), 'author'), 'left')
						->on('author.id', '=', 'page.created_by_id')
					->join(array(User::tableName(), 'updator'), 'left')
						->on('updator.id', '=', 'page.updated_by_id')
					->where('slug', '=', $slug)
					->where('parent_id', '=', $parent_id)
					->where('published_on', '<=', DB::expr('NOW()'))
					->where('status_id', 'in', $statuses)
					->limit(1)
					->cache_tags( array('pages') )
					->cached((int)Kohana::$config->load('global.cache.front_page'))
					->as_object()
					->execute()
					->current();


				if( $page )
				{
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
				else
					return FALSE;
			}
		}
		else
			return self::$pages_cache[ $page_cache_id ];
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

		$parent = FALSE;

		foreach ($urls as $page_slug)
		{
			$url = ltrim($url . '/' . $page_slug, '/');

			if( $page = self::findBySlug($page_slug, $parent) )
			{
				// check for behavior
				if( !empty( $page->behavior_id ) )
				{
					$page->{$page->behavior_id} = Behavior::load($page->behavior_id, $page, $url, $uri);

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
		$page_class = __CLASS__;

		$statuses = array(Model_Page::STATUS_REVIEWED, Model_Page::STATUS_PUBLISHED, Model_Page::STATUS_HIDDEN);

		$page = DB::select('page.*')
			->select(array('author.name', 'author'))
			->select(array('updator.name', 'updator'))
			->from(array(Model_Page::tableName(), 'page'))
			->join(array(User::tableName(), 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array(User::tableName(), 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id')
			->where('page.id', '=', $id)
			->where('published_on', '<=', DB::expr('NOW()'))
			->where('status_id', 'in', $statuses)
			->limit(1)
			->cache_tags( array('pages') )
			->cached((int)Kohana::$config->load('global.cache.front_page'))
			->as_object()
			->execute()
			->current();

		if( $page )
		{
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
		else
			return FALSE;
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
			return FALSE;
		}
		else if ($this->level == $level)
		{
			return $this;
		}

		return $this->parent->parent($level);
	}
	
	/**
	 * 
	 * @param string $snippet_name
	 * @param array $vars
	 * @param integer $cache_lifetime
	 */
	public function snippet($snippet_name, $vars = NULL, $cache_lifetime = 3600)
	{
		Snippet::render($snippet_name, $vars, $cache_lifetime);
	}

	/**
	 * 
	 * @deprecated
	 */
	public function includeSnippet($snippet_name, $vars = NULL, $cache_lifetime = NULL)
	{
		return $this->snippet($snippet_name, $vars, $cache_lifetime);
	}

	public function render_layout()
	{
		$layout_name = $this->layout();

		$layout = new Model_File_Layout($layout_name);

		if(!$layout->is_exists())
		{
			throw new  Kohana_Exception('Layout file :file not found!', array(
				':file' => $layout_name
			));
		}

		$mime = File::mime_by_ext(pathinfo($this->url(), PATHINFO_EXTENSION));

		if($mime)
		{
			Request::current()->headers('Content-Type',  $mime );
		}
		
		View_Front::set_global('page_object', $this);

		return View_Front::factory($layout->get_file())
			->set('page', $this);
	}

	/**
	 * find the layoutId of the page where the layout is set
	 */	
	public function layout()
	{
		if ( !empty($this->layout_file) )
		{
			return $this->layout_file;
		}
		else if( ! empty($this->parent) )
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
		if ($this->needs_login == Model_Page::LOGIN_INHERIT AND $this->parent)
		{
			return $this->parent->needs_login();
		}

		return $this->needs_login;
	}

	private function _load_parts()
	{
		$this->part = new stdClass;
		$parts = DB::select('name', 'content', 'content_html')
			->from(Model_Page_Part::tableName())
			->where('page_id', '=', $this->id)
			->cache_tags( array('page_parts') )
			->as_object('Model_Page_Part')
			->cached((int)Kohana::$config->load('global.cache.page_parts'))
			->execute();

		foreach ( $parts as $part_obj )
		{
			$this->part->{$part_obj->name} = $part_obj;
		}
	}

	/**
	 * 
	 * @return array
	 */
	private function _load_tags()
	{
		return DB::select('tag.id', 'tag.name')
			->from(array(Model_Page_Tag::tableName(), 'page_tag'))
			->join(array(Model_Tag::tableName(), 'tag'), 'left')
				->on('page_tag.page_id', '=', 'tag.id')
			->where('page_tag.page_id', '=', $this->id)
			->cache_tags( array('page_tags') )
			->cached((int)Kohana::$config->load('global.cache.tags'))
			->execute()
			->as_array('tag.id', 'tag.name');
	}

} // end Model_Page_Front class