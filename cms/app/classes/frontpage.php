<?php defined('SYSPATH') or die('No direct access allowed.');

class FrontPage
{
    const STATUS_DRAFT = 1;
    const STATUS_REVIEWED = 50;
    const STATUS_PUBLISHED = 100;
    const STATUS_HIDDEN = 101;
    
    const LOGIN_NOT_REQUIRED = 0;
    const LOGIN_REQUIRED = 1;
    const LOGIN_INHERIT = 2;

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
	
	public static function not_found($message = 'Страница не найдена')
	{
		Observer::notify('page_not_found');
		throw new HTTP_Exception_404($message);
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
		$this->_loadParts();
		
    }
    
    protected function setUrl()
    {
        $this->url = trim($this->parent->url .'/'. $this->slug, '/');
    }
    
    public function id() { return $this->id; }
    public function title() { return $this->title; }
    public function breadcrumb() { return $this->breadcrumb; }
    public function author() { return $this->author; }
    public function authorId() { return $this->author_id; }
    public function updator() { return $this->updator; }
    public function updatorId() { return $this->updator_id; }
    public function slug() { return $this->slug; }
    public function keywords() { return $this->keywords; }
    public function description() { return $this->description; }
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
            $this->_loadTags();
		}
            
        return $this->tags;
    }
    
    public function link($label = NULL, $options = array(), $check_current = TRUE)
    {
        if ($label == NULL)
		{
            $label = $this->title();
		}
		
		if ($check_current === TRUE)
		{
			if (strpos(Request::current()->url(), $this->url) === 1)
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
    
    public function hasContent($part, $inherit = FALSE)
    {
		
		if (isset($this->part->{$part}))
		{
			return TRUE;
		}
        else if ($inherit && $this->parent)
        {
            return $this->parent->hasContent($part, TRUE);
        }
		
		return FALSE;
    }
    
    public function content($part = 'body', $inherit = FALSE)
    {		
		if (isset($this->part->{$part}))
		{
			return FrontView::factory()
				->set('page', $this)
				->render_html($this->part->{$part}->content_html);
		}
        else if ($inherit AND $this->parent)
        {
            return $this->parent->content($part, TRUE);
        }
		
		return NULL;
    }
    
    public function previous()
    {
        if ($this->parent)
		{
            return $this->parent->children(array(
                'limit' => 1,
                'where' => 'page.id < '. $this->id,
                'order' => 'page.created_on DESC'
            ));
		}
    }
    
    public function next()
    {
        if ($this->parent)
		{
            return $this->parent->children(array(
                'limit' => 1,
                'where' => 'page.id > '. $this->id,
                'order' => 'page.created_on ASC'
            ));
		}
    }
    
    public function children($args = NULL, $value = array(), $include_hidden = FALSE)
    {
        $page_class = __CLASS__;
        
        // Collect attributes...
        $where   = isset($args['where'])  ? $args['where']: '';
        $order   = isset($args['order'])  ? $args['order']: 'page.position DESC, page.id';
        $offset  = isset($args['offset']) ? $args['offset']: 0;
        $limit   = isset($args['limit'])  ? $args['limit']: 0;
        
        // auto offset generated with the page param
        if ($offset == 0 && isset($_GET['page']))
            $offset = ((int)$_GET['page'] - 1) * $limit;
        
        // Prepare query parts
        $where_string = trim($where) == '' ? '' : "AND ".$where;
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
        
		$statuses = array(self::STATUS_REVIEWED, self::STATUS_PUBLISHED);
		if($include_hidden)
		{
			$statuses[] = self::STATUS_HIDDEN;
		}
		
		$sql = (string) DB::select('page.*')
			->select(array('author.name', 'author'), array('author.id', 'author_id'))
			->select(array('updator.name', 'updator'), array('updator.id', 'updator_id'))
			->from(array(Page::tableName(), 'page'))
			->join(array(User::tableName(), 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array(User::tableName(), 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id')
			->where('parent_id', '=', $this->id)
			->where('published_on', '<=', DB::expr('NOW()'))
			->where('status_id', 'in', $statuses);
			
			
        // Prepare SQL
        $sql .= "$where_string ORDER BY $order $limit_string";
        
        $pages = array();
        
        // hack to be able to redefine the page class with behavior
        if ( ! empty($this->behavior_id))
        {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($this->behavior_id);
        }
		
		$query = DB::query(Database::SELECT, $sql)
			->cached((int)Kohana::$config->load('global.cache.front_page'))
			->execute();
        
        // Run!
        if ($query)
        {
            foreach ($query as $object)
            {
                $page = new $page_class($object, $this);
				
				Observer::notify('frontpage_children_found', array($page));
				
                $pages[] = $page;
            }
        }
        
        if ($limit == 1)
            return isset($pages[0]) ? $pages[0]: FALSE;
        
        return $pages;
    }
    
	public function childrenCount($args=NULL, $value=array(), $include_hidden=FALSE)
	{
		// Collect attributes...
		$where   = isset($args['where']) ? $args['where']: '';
		$order   = isset($args['order']) ? $args['order']: 'position, id';
		$limit   = isset($args['limit']) ? $args['limit']: 0;
		$offset  = 0;

		// Prepare query parts
		$where_string = trim($where) == '' ? '' : "AND ".$where;
		$limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';

		$statuses = array(self::STATUS_REVIEWED, self::STATUS_PUBLISHED);
		if($include_hidden)
		{
			$statuses[] = self::STATUS_HIDDEN;
		}
		
		$sql = (string) DB::select(array('COUNT("*")', 'total'))
			->from(array(Page::tableName(), 'page'))
			->where('published_on', '<=', DB::expr('NOW()'))
			->where('status_id', 'in', $statuses);

		// Prepare SQL
		$sql .= "$where_string ORDER BY $order $limit_string";

		return (int) DB::query(Database::SELECT, $sql)
			->cached((int)Kohana::$config->load('global.cache.front_page'))
			->execute()
			->get('total');
	}
	
	public static function findBySlug( $slug, $parent )
	{		
		$page_cache_id = (is_array($slug) ? join($slug) : $slug) . (isset($parent->id) ? $parent->id : 0);
		
		if( !isset(self::$pages_cache[ $page_cache_id ]) )
		{
			$page_class = __CLASS__;
			
			$page = FALSE;
			
			Observer::notify('frontpage_byslug_before_found', array(&$page, $slug, $parent));
			
			if (is_object($page) && ($page instanceof $page_class))
			{
				return $page;
			}
			else
			{
				$parent_id = $parent ? $parent->id: 0;
				$statuses = array(self::STATUS_REVIEWED, self::STATUS_PUBLISHED, self::STATUS_HIDDEN);
				
				$page = DB::select('page.*')
					->select(array('author.name', 'author'))
					->select(array('updator.name', 'updator'))
					->from(array(Page::tableName(), 'page'))
					->join(array(User::tableName(), 'author'), 'left')
						->on('author.id', '=', 'page.created_by_id')
					->join(array(User::tableName(), 'updator'), 'left')
						->on('updator.id', '=', 'page.updated_by_id')
					->where('slug', '=', $slug)
					->where('parent_id', '=', $parent_id)
					->where('published_on', '<=', DB::expr('NOW()'))
					->where('status_id', 'in', $statuses)
					->limit(1)
					->cache_key( 'FrontPage::slug::' . $slug . '::parent_id::' . $parent_id )
					->cached((int)Kohana::$config->load('global.cache.front_page'))
					->as_object()
					->execute()
					->current();
				
				
				if( $page )
				{
					// hook to be able to redefine the page class with behavior
					if ( !empty($parent->behavior_id) )
					{
						// will return Page by default (if not found!)
						$page_class = Behavior::loadPageHack($parent->behavior_id);
					}
					
					// create the object page
					$page = new $page_class($page, $parent);
					
					$pages_cache[ $page_cache_id ] = $page;
					
					Observer::notify('frontpage_byslug_found', array($page));
					
					return $page;
				}
				else
					return FALSE;
			}
		}
		else
			return self::$pages_cache[ $page_cache_id ];
	}
	
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
				if( $page->behavior_id != '' )
				{
					// add a instance of the behavior with the name of the behavior 
					$params = preg_split('/\//', substr($uri, strlen($url)), -1, PREG_SPLIT_NO_EMPTY);
					$page->{$page->behavior_id} = Behavior::load($page->behavior_id, $page, $params);
					
					return $page;
				}
			}
			else
				break;
			
			$parent = $page;
		}
		
		return $page;
	}
	
	public static function findById( $id )
	{
		$page_class = __CLASS__;
		
		$page = DB::select('page.*')
			->select(array('author.name', 'author'))
			->select(array('updator.name', 'updator'))
			->from(array(Page::tableName(), 'page'))
			->join(array(User::tableName(), 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array(User::tableName(), 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id')
			->where('page.id', '=', $id)
			->where('published_on', '<=', DB::expr('NOW()'))
			->where('status_id', 'in', $statuses)
			->limit(1)
			->cache_key( 'FrontPage::id::' . $id )
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
				$page_class = Behavior::loadPageHack($parent->behavior_id);
			}
			
			// create the object page
			$page = new $page_class($page, $parent);
			
			Observer::notify('frontpage_byid_found', array($page));
			
			return $page;
		}
		else
			return FALSE;
	}
	
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
    
    public function includeSnippet($snippet_name, $vars = NULL)
    {
		$snippet = new Model_File_Snippet($snippet_name);
		
		if(!$snippet->is_exists())
		{
			return NULL;
		}

		return FrontView::factory($snippet->get_file(), $vars)
			->set('page', $this);
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
			Request::current()->response()->headers('Content-Type',  $mime );
		}
		
		return FrontView::factory($layout->get_file())
			->set('page', $this);
	}

	/**
	 * find the layoutId of the page where the layout is set
	 */	
	public function layout()
    {
        if ($this->layout_file)
		{
            return $this->layout_file;
		}
        else if ($this->parent)
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
    public function getLoginNeeded()
    {
        if ($this->needs_login == self::LOGIN_INHERIT && $this->parent)
		{
            return $this->parent->getLoginNeeded();
		}
        
		return $this->needs_login;
    }
	
	private function _loadParts()
    {
		$this->part = new stdClass;
		$parts = DB::select('name', 'content', 'content_html')
			->from(PagePart::tableName())
			->where('page_id', '=', $this->id)
			->cache_key('pageParts::page_id::'.$this->id)
			->as_object('PagePart')
			->cached((int)Kohana::$config->load('global.cache.page_parts'))
			->execute();

		foreach ( $parts as $part_obj )
		{
			$this->part->{$part_obj->name} = $part_obj;
		}
	}
    
    private function _loadTags()
    {
		$this->tags = DB::select('tag.id', 'tag.name')
			->from(array(PageTag::tableName(), 'tag'))
			->join('tag', 'left')
				->on('page_tag.page_id', '=', 'tag.id')
			->where('page_tag.page_id', '=', $this->id)
			->cache_key( 'pageTags::page_id::' . $this->id )
			->cached((int)Kohana::$config->load('global.cache.tags'))
			->execute()
			->as_array('tag.id', 'tag.name');
    }
	
} // end FrontPage class