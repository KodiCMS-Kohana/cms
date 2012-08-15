<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Flexo CMS.
 *
 * Flexo CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flexo CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flexo CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Flexo CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package Flexo
 * @subpackage models
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

/**
 * class Page
 *
 * apply methodes for page, layout and snippet of a page
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 *
 * -- TAGS --
 * id()
 * title()
 * breadcrumb()
 * author()
 * slug()
 * url()
 *
 * link([label], [class])
 * date([format])
 *
 * hasContent(part_name, [inherit])
 * content([part_name], [inherit])
 * breadcrumbs([between])
 *
 * children([arguments :limit :offset :order])
 * find(url)
 
 todo:
 
 <r:navigation />

 Renders a list of links specified in the urls attribute according to three states:

 normal specifies the normal state for the link
 here specifies the state of the link when the url matches the current page’s URL
 selected specifies the state of the link when the current page matches is a child of the specified url
 The between tag specifies what should be inserted in between each of the links.

 Usage:
 <r:navigation urls="[Title: url | Title: url | ...]">
   <r:normal><a href="<r:url />"><r:title /></a></r:normal>
   <r:here><strong><r:title /></strong></r:here>
   <r:selected><strong><a href="<r:url />"><r:title /></a></strong></r:selected>
   <r:between> | </r:between>
 </r:navigation>
 
 **/

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
    
    public $parent = false;
    public $level = false;
    public $tags = false;

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
    public function url() { return CMS_URL . $this->url . ($this->url != '' ? URL_SUFFIX: ''); }
    
    public function level()
    {
        if ($this->level === false)
            $this->level = empty($this->url) ? 0 : substr_count($this->url, '/')+1;
        
        return $this->level;
    }
    
    public function tags()
    {
        if ( ! $this->tags)
            $this->_loadTags();
            
        return $this->tags;
    }
    
    public function link($label=null, $options='', $check_current = false)
    {
        if ($label == null)
            $label = $this->title();
		
		if ($check_current !== false)
		{
			if (strpos(CURRENT_URI, $this->url) === 1)
			{
				if ($options != '' && stristr($options, 'class') !== false)
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
    
    public function hasContent($part, $inherit=false)
    {
        $connection = Record::getConnection();
		
		if (!isset($this->part) || !is_object($this->part))
			$this->part = new stdClass;
		
		if (!empty($this->part->{$part}))
			return true;
		
	    $sql = 'SELECT name, content_html FROM '.TABLE_PREFIX.'page_part WHERE name=? AND page_id=? LIMIT 1';
		
		$stmt = $connection->prepare($sql);
	    $stmt->execute(array($part, $this->id));
		
		if ($obj = $stmt->fetchObject())
		{
			$this->part->{$part} = $obj;
			
			return true;
        }
        else if ($inherit && $this->parent)
        {
            return $this->parent->hasContent($part, true);
        }
		
		return false;
    }
    
    public function content($part='body', $inherit=false)
    {
		$connection = Record::getConnection();
		
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
		
	    $sql = 'SELECT name, content_html FROM '.TABLE_PREFIX.'page_part WHERE name=? AND page_id=? LIMIT 1';
	    
	    $stmt = $connection->prepare($sql);
	    $stmt->execute(array($part, $this->id));
		
		if ($obj = $stmt->fetchObject())
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
            return $this->parent->content($part, true);
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
    
    public function children($args=null, $value=array(), $include_hidden=false)
    {
        $conn = Record::getConnection();
        
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
             . 'FROM '.TABLE_PREFIX.'page AS page '
             . 'LEFT JOIN '.TABLE_PREFIX.'user AS author ON author.id = page.created_by_id '
             . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
             . 'WHERE parent_id = '.$this->id.' AND published_on <= NOW() AND (status_id='.self::STATUS_REVIEWED.' OR status_id='.self::STATUS_PUBLISHED.($include_hidden ? ' OR status_id='.self::STATUS_HIDDEN: '').') '
             . "$where_string ORDER BY $order $limit_string";
        
        $pages = array();
        
        // hack to be able to redefine the page class with behavior
        if ( ! empty($this->behavior_id))
        {
            // will return Page by default (if not found!)
            $page_class = Behavior::loadPageHack($this->behavior_id);
        }
        
        // Run!
        if ($stmt = $conn->prepare($sql))
        {
            $stmt->execute($value);
            
            while ($object = $stmt->fetchObject())
            {
                $page = new $page_class($object, $this);
				
				Observer::notify('frontpage_children_found', array($page));
				
                $pages[] = $page;
            }
        }
        
        if ($limit == 1)
            return isset($pages[0]) ? $pages[0]: false;
        
        return $pages;
    }
    
    public function childrenCount($args=null, $value=array(), $include_hidden=false)
    {
        $conn = Record::getConnection();
        
        // Collect attributes...
        $where   = isset($args['where']) ? $args['where']: '';
        $order   = isset($args['order']) ? $args['order']: 'position, id';
        $limit   = isset($args['limit']) ? $args['limit']: 0;
        $offset  = 0;
        
        // Prepare query parts
        $where_string = trim($where) == '' ? '' : "AND ".$where;
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
        
        // Prepare SQL
        $sql = 'SELECT COUNT(*) AS nb_rows FROM '.TABLE_PREFIX.'page '
             . 'WHERE parent_id = '.$this->id.' AND published_on <= NOW() AND (status_id='.self::STATUS_REVIEWED.' OR status_id='.self::STATUS_PUBLISHED.($include_hidden ? ' OR status_id='.self::STATUS_HIDDEN: '').') '
             . "$where_string ORDER BY $order $limit_string";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($value);
        
        return (int) $stmt->fetchColumn();
    }
	
	public static function findBySlug( $slug, $parent )
	{		
		$page_cache_id = (is_array($slug) ? join($slug) : $slug) . (isset($parent->id) ? $parent->id : 0);
		
		if( !isset(self::$pages_cache[ $page_cache_id ]) )
		{
			$connection = Record::getConnection();
			
			$page_class = __CLASS__;
			
			$page = false;
			
			Observer::notify('frontpage_byslug_before_found', array(&$page, $slug, $parent));
			
			if (is_object($page) && ($page instanceof $page_class))
			{
				return $page;
			}
			else
			{
				$parent_id = $parent ? $parent->id: 0;
				
				$sql = 'SELECT page.*, author.name AS author, updator.name AS updator '
					 . 'FROM '.TABLE_PREFIX.'page AS page '
					 . 'LEFT JOIN '.TABLE_PREFIX.'user AS author ON author.id = page.created_by_id '
					 . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
					 . 'WHERE slug = ? AND parent_id = ? AND published_on <= NOW() AND (status_id='.self::STATUS_REVIEWED.' OR status_id='.self::STATUS_PUBLISHED.' OR status_id='.self::STATUS_HIDDEN.')';
				
				$stmt = $connection->prepare( $sql );
				
				$stmt->execute( array($slug, $parent_id) );
				
				if( $page = $stmt->fetchObject() )
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
					return false;
			}
		}
		else
			return self::$pages_cache[ $page_cache_id ];
	}
	
    public static function find( $uri )
	{
		$uri = trim($uri, '/');
		
		// adding the home root
		$urls = array_merge(array(''), preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY));
		$url = '';
		
		$page = new stdClass;
		$page->id = 0;
		
		$parent = false;
		
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
		$connection = Record::getConnection();
		
		$page_class = __CLASS__;
		
		$sql = 'SELECT page.*, author.name AS author, updator.name AS updator '
				 . 'FROM '.TABLE_PREFIX.'page AS page '
				 . 'LEFT JOIN '.TABLE_PREFIX.'user AS author ON author.id = page.created_by_id '
				 . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
				 . 'WHERE page.id = ? AND published_on <= NOW() AND (status_id='.self::STATUS_REVIEWED.' OR status_id='.self::STATUS_PUBLISHED.' OR status_id='.self::STATUS_HIDDEN.') LIMIT 1';
		
		$stmt = $connection->prepare( $sql );
		$stmt->execute( array($id) );
		
		if( $page = $stmt->fetchObject() )
		{
			if ($page->parent_id)
				$parent = self::findById($page->parent_id);
			else
				$parent = null;
			
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
			return false;
	}
	
    public function parent($level=null)
    {
        if ($level === null)
            return $this->parent;
        
        if ($level > $this->level)
            return false;
        else if ($this->level == $level)
            return $this;
        else 
            return $this->parent->parent($level);
    }
    
    public function includeSnippet($snippet_name, $vars = null)
    {
		$layout_name = explode('/', $this->_getLayoutFile());
		
		$snippet_file = SNIPPETS_ROOT.DIRECTORY_SEPARATOR.$snippet_name.'.php';
		
		if (file_exists($snippet_file))
		{
			if (is_array($vars))
				extract($vars);
				
			include($snippet_file);
		}
		else
			exit ('Snippet '.$snippet_name.' not found!');
    }
    
    public function executionTime()
    {
        return execution_time();
    }
     
    private function _executeLayout()
    {		
		$layout_name = $this->_getLayoutFile();
		
		$layout_file = LAYOUTS_ROOT.DIRECTORY_SEPARATOR.$layout_name.'.'.LAYOUTS_EXT;
		
        if (file_exists($layout_file))
        {
            // if content-type not set, we set html as default			
			if (($p = strpos($layout_name, '.')) !== false && ($ext = substr($layout_name, $p+1)) && isset(Layout::$mimes[$ext]))
				$content_type = Layout::$mimes[$ext];
			else
				$content_type = 'text/html';
			
            // set content-type and charset of the page
            header('Content-Type: '.$content_type.'; charset=UTF-8');
            
			include($layout_file);
        }
		else
			throw new Exception('Layout file '.$layout_name.' not found!');
    }
    
	public function display()
	{
		$this->_executeLayout();
	}
	
	/**
	 * find the layoutId of the page where the layout is set
	 */	
	private function _getLayoutFile()
    {
        if ($this->layout_file)
            return $this->layout_file;
        else if ($this->parent)
            return $this->parent->_getLayoutFile();
        else
            return null;
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
        $conn = Record::getConnection();
        $this->tags = array();
        
        $sql = "SELECT tag.id AS id, tag.name AS tag FROM ".TABLE_PREFIX."page_tag AS page_tag, ".TABLE_PREFIX."tag AS tag ".
               "WHERE page_tag.page_id={$this->id} AND page_tag.tag_id = tag.id";
        
        if ( ! $stmt = $conn->prepare($sql))
            return;
            
        $stmt->execute();
        
        // Run!
        while ($object = $stmt->fetchObject())
             $this->tags[$object->id] = $object->tag;
    }
	
} // end FrontPage class