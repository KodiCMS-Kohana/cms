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
	
    
    public function __construct($object, $parent)
    {
        $this->parent = $parent;
        
        foreach ($object as $key => $value) {
            $this->$key = $value;
        }
        
        if ($this->parent)
        {
            $this->setUrl();
        }
		
		$this->level = $this->level();
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
    public function url() { return URL::base() . $this->url . ($this->url != '' ? URL_SUFFIX: ''); }
    
    public function level()
    {
        if ($this->level === FALSE)
            $this->level = empty($this->url) ? 0 : substr_count($this->url, '/')+1;
        
        return $this->level;
    }
    
    public function tags()
    {
        if ( ! $this->tags)
            $this->_loadTags();
            
        return $this->tags;
    }
    
    public function link($label=NULL, $options='', $check_current = FALSE)
    {
        if ($label == NULL)
            $label = $this->title();
		
		if ($check_current !== FALSE)
		{
			if (strpos(Request::current()->url(), $this->url) === 1)
			{
				if ($options != '' && stristr($options, 'class') !== FALSE)
				{
					preg_match('/(.*)class=(\'|\")([^\'\"]*)(\'|\")(.*)/i', $options, $m);
					$options = $m[1] . ' class="'. $m[3] .' current" ' . $m[5];
				}
				else
					$options .= ' class="current"';
			}
		}
        
        return sprintf('<a href="%s" %s>%s</a>',
               $this->url(),
               $options,
               $label
        );
    }
    
	/**
	* http://php.net/strftime
	* exemple (can be useful):
	*  '%a, %e %b %Y'      -> Wed, 20 Dec 2006 <- (default)
	*  '%A, %e %B %Y'      -> Wednesday, 20 December 2006
	*  '%B %e, %Y, %H:%M %p' -> December 20, 2006, 08:30 pm
	*/
    public function date($format='%m/%d/%y %H:%M:%S %p', $which_one='publish')
    {
        if ($which_one == 'update' || $which_one == 'updated')
            return strftime($format, strtotime($this->updated_on));
        else if ($which_one == 'publish' || $which_one == 'published')
            return strftime($format, strtotime($this->published_on));
        else
            return strftime($format, strtotime($this->created_on));
    }
    
    public function breadcrumbs($separator='&gt;', $level = 0)
    {
        $url = '';
        $path = '';
        $paths = explode('/', '/'.$this->slug);
        $nb_path = count($paths);
        
        $out = '<div class="breadcrumb">'."\n";
        
        if ($this->parent && $this->level > $level)
            $out .= $this->parent->_inversedBreadcrumbs($separator, $level);
        
        return $out . '<span class="breadcrumb-current">'.$this->breadcrumb().'</span></div>'."\n";
        
    }
	
	private function _inversedBreadcrumbs($separator, $level)
    {
        $out = '<a href="'.$this->url().'" title="'.$this->breadcrumb.'">'.$this->breadcrumb.'</a> <span class="breadcrumb-separator">'.$separator.'</span> '."\n";
    
        if ($this->parent && $this->level > $level)
            return $this->parent->_inversedBreadcrumbs($separator, $level) . $out;
        
        return $out;
    }
    
    public function hasContent($part, $inherit=FALSE)
    {
		if (!isset($this->part) || !is_object($this->part))
			$this->part = new stdClass;
		
		if (!empty($this->part->{$part}))
			return TRUE;
		
		$obj = DB::select('name', 'content_html')
			->from('page_part')
			->where('name', '=', $part)
			->where('page_id', '=', $this->id)
			->limit(1)
			->as_object()
			->cached()
			->execute()
			->current();
		
		if ($obj)
		{
			$this->part->{$part} = $obj;
			
			return TRUE;
        }
        else if ($inherit && $this->parent)
        {
            return $this->parent->hasContent($part, TRUE);
        }
		
		return FALSE;
    }
    
    public function content($part='body', $inherit=FALSE)
    {
		if (!isset($this->part) || !is_object($this->part))
			$this->part = new stdClass;
		
		if (!empty($this->part->{$part}))
		{
			ob_start();
			eval('?>' . $this->part->{$part}->content_html);
			$out = ob_get_contents();
			ob_end_clean();
			
			return $out;
		}
		
		$obj = DB::select('name', 'content_html')
			->from('page_part')
			->where('name', '=', $part)
			->where('page_id', '=', $this->id)
			->limit(1)
			->as_object()
			->cached()
			->execute()
			->current();
	
		if ($obj)
		{
			$this->part->{$part} = $obj;
		
			ob_start();
			eval('?>' . $this->part->{$part}->content_html);
			$out = ob_get_contents();
			ob_end_clean();
			
			return $out;
        }
        else if ($inherit && $this->parent)
        {
            return $this->parent->content($part, TRUE);
        }
    }
    
    public function previous()
    {
        if ($this->parent)
            return $this->parent->children(array(
                'limit' => 1,
                'where' => 'page.id < '. $this->id,
                'order' => 'page.created_on DESC'
            ));
    }
    
    public function next()
    {
        if ($this->parent)
            return $this->parent->children(array(
                'limit' => 1,
                'where' => 'page.id > '. $this->id,
                'order' => 'page.created_on ASC'
            ));
    }
    
    public function children($args=NULL, $value=array(), $include_hidden=FALSE)
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
        
        // Prepare SQL
        $sql = 'SELECT page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id '
             . 'FROM page AS page '
             . 'LEFT JOIN user AS author ON author.id = page.created_by_id '
             . 'LEFT JOIN user AS updator ON updator.id = page.updated_by_id '
             . 'WHERE parent_id = '.$this->id.' AND published_on <= NOW() AND (status_id='.self::STATUS_REVIEWED.' OR status_id='.self::STATUS_PUBLISHED.($include_hidden ? ' OR status_id='.self::STATUS_HIDDEN: '').') '
             . "$where_string ORDER BY $order $limit_string";
        
        $pages = array();
        
        // hack to be able to redefine the page class with behavior
        if ( ! empty($this->behavior_id))
        {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($this->behavior_id);
        }
		
		$query = DB::query(Database::SELECT, $sql)
			->cached()
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

		// Prepare SQL
		$sql = 'SELECT COUNT(*) AS nb_rows FROM page '
				. 'WHERE parent_id = '.$this->id.' AND published_on <= NOW() AND (status_id='.self::STATUS_REVIEWED.' OR status_id='.self::STATUS_PUBLISHED.($include_hidden ? ' OR status_id='.self::STATUS_HIDDEN: '').') '
				. "$where_string ORDER BY $order $limit_string";

		return (int) DB::query(Database::SELECT, $sql)
			->cached()
			->execute()
			->get('nb_rows');
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
				
				$page = DB::select('page.*', array('author.name', 'author'), array('updator.name', 'updator'))
					->from('page')
					->join(array('user', 'author'), 'left')
						->on('author.id', '=', 'page.created_by_id')
					->join(array('user', 'updator'), 'left')
						->on('updator.id', '=', 'page.updated_by_id')
					->where('page.slug', '=', $slug)
					->where('page.parent_id', '=', $parent_id)
					->where('published_on', '<=', DB::expr('NOW()'))
					->where_open()
						->where('status_id', '=', self::STATUS_REVIEWED)
						->or_where('status_id', '=', self::STATUS_PUBLISHED)
						->or_where('status_id', '=', self::STATUS_HIDDEN)
					->where_close()
					->limit(1)
					->cached()
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
		
		$page = DB::select('page.*', array('author.name', 'author'), array('updator.name', 'updator'))
			->from('page')
			->join(array('user', 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array('user', 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id')
			->where('page.id', '=', $id)
			->where('published_on', '<=', DB::expr('NOW()'))
			->where_open()
				->where('status_id', '=', self::STATUS_REVIEWED)
				->or_where('status_id', '=', self::STATUS_PUBLISHED)
				->or_where('status_id', '=', self::STATUS_HIDDEN)
			->where_close()
			->limit(1)
			->cached()
			->execute()
			->current();
		
		if( $page )
		{
			if ($page->parent_id)
				$parent = self::findById($page->parent_id);
			else
				$parent = NULL;
			
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
	
    public function parent($level=NULL)
    {
        if ($level === NULL)
            return $this->parent;
        
        if ($level > $this->level)
            return FALSE;
        else if ($this->level == $level)
            return $this;
        else 
            return $this->parent->parent($level);
    }
    
    public function includeSnippet($snippet_name, $vars = NULL, $to_string = FALSE)
    {
		$snippet_file = SNIPPETS_SYSPATH.$snippet_name.EXT;
		
		if (!file_exists($snippet_file))
			return NULL;

		if (is_array($vars))
			extract($vars);

		if($to_string)
		{
			// Capture the view output
			ob_start();
			include $snippet_file;
			return ob_get_clean();
		}

		include($snippet_file);
    }
	
	public function render_layout()
	{
		$layout_name = $this->layout();
		$layout_file = LAYOUTS_SYSPATH.$layout_name.EXT;

		if (!file_exists($layout_file))
			throw new Core_Exception('Layout file :file not found!', array(
				':file' => $layout_name
			));

		// if content-type not set, we set html as default			
		if (($p = strpos($layout_name, '.')) !== FALSE && ($ext = substr($layout_name, $p+1)) && isset(Layout::$mimes[$ext]))
			$content_type = File::mime_by_ext ( $ext );
		else
			$content_type = 'text/html';

		Request::current()->response()->headers('Content-Type', $content_type . '; charset=UTF-8');
		include($layout_file);
	}

	/**
	 * find the layoutId of the page where the layout is set
	 */	
	public function layout()
    {
        if ($this->layout_file)
            return $this->layout_file;
        else if ($this->parent)
            return $this->parent->layout();
        else
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
            return $this->parent->getLoginNeeded();
        else
            return $this->needs_login;
    }
    
    private function _loadTags()
    {
		$this->tags = DB::select('tag.id', 'tag.name')
			->from('page_tag')
			->join('tag', 'left')
				->on('page_tag.page_id', '=', 'tag.id')
			->where('page_tag.page_id', '=', $this->id)
			->cached()
			->execute()
			->as_array('tag.id', 'tag.name');
    }
	
} // end FrontPage class