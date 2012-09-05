<?php defined('SYSPATH') or die('No direct access allowed.');

class Page extends Record
{
    const TABLE_NAME = 'page';
    
    const STATUS_DRAFT = 1;
    const STATUS_REVIEWED = 50;
    const STATUS_PUBLISHED = 100;
    const STATUS_HIDDEN = 101;

    const LOGIN_NOT_REQUIRED = 0;
    const LOGIN_REQUIRED = 1;
    const LOGIN_INHERIT = 2;
    
    public $title;
    public $slug;
    public $breadcrumb;
    public $keywords;
    public $description;
    public $content;
    public $parent_id;
    public $layout_file;
    public $behavior_id;
    public $status_id;
    public $comment_status;
    
    public $created_on;
    public $published_on;
    public $updated_on;
    public $created_by_id;
    public $updated_by_id;
    public $position;
    public $needs_login;
    
    public function beforeInsert()
    {		
        $this->created_on = date('Y-m-d H:i:s');
        $this->created_by_id = AuthUser::getId();
        
        $this->updated_on = $this->created_on;
        $this->updated_by_id = $this->created_by_id;
        
        if ($this->status_id == Page::STATUS_PUBLISHED)
            $this->published_on = date('Y-m-d H:i:s');
        
		if ($this->position == 0)
		{
			$last_position = DB::select(array('MAX("page.position")', 'pos'))
				->from(self::TABLE_NAME)
				->where('parent_id', '=', $this->parent_id)
				->execute()
				->get('pos', 0);
			
			$this->position = ((int) $last_position) + 1;
		}
		
        return true;
    }
	
	public function afterInsert()
	{
		$page = DB::select('page.id')
			->from(self::TABLE_NAME)
			->where('page.id', '<>', $this->id)
			->where('page.slug', '=', $this->slug)
			->where('page.parent_id', '=', $this->parent_id)
			->execute()
			->get('id');
		
		if ($page !== NULL)
		{
			DB::update(self::TABLE_NAME)
				->set(array(
					'slug' => DB::expr('CONCAT(page.slug, "-", page.id)')
				))
				->where('id', '=', $this->id)
				->where('parent_id', '=', $this->parent_id)
				->execute();
		}
		
		return true;
	}
    
    public function beforeUpdate()
    {	
        if( empty($this->published_on) && $this->status_id == Page::STATUS_PUBLISHED)
        {
            $this->published_on = date('Y-m-d H:i:s');
        }
        
        $this->updated_by_id = AuthUser::getId();
        $this->updated_on = date('Y-m-d H:i:s');
        
        return true;
    }
	
	public function afterUpdate()
	{
		return $this->afterInsert();
	}
	
	public function beforeDelete()
	{
		// need to delete subpages
		self::deleteByParentId($this->id);
		
		DB::delete('page_permission')
			->where('page_id', '', $this->id)
			->execute();
		
		DB::delete('page_tag')
			->where('page_id', '', $this->id)
			->execute();

		return true;
	}

    public function getUri()
    {
        $result = null;

        $parent = $this->findById($this->parent_id);
		
        if( $parent != null && $parent->slug != '' )
		{
            $result = $parent->getUri().'/'.$this->slug;
        }
        else
		{
            $result = $this->slug;
        }

        return $result;
    }
    
    public function getTags()
    {
        $tablename_page_tag = self::tableName('PageTag');
        $tablename_tag = self::tableName('Tag');
		
		return DB::select(array('tag.id', 'id'), array('tag.name', 'tag'))
			->from(array($tablename_page_tag, 'page_tag'))
			->join(array($tablename_tag, 'tag'), 'left')
				->on('page_tag.tag_id', '=', 'tag.id')
			->where('page_tag.page_id', '=', $this->id)
			->execute()
			->as_array('id', 'tag');
    }
    
    public function saveTags($tags)
    {
        if( is_string($tags) )
            $tags = explode(',', $tags);
        
        $tags = array_map('trim', $tags);
        
        $current_tags = $this->getTags();
        
        // no tag before! no tag now! ... nothing to do!
        if( count($tags) == 0 && count($current_tags) == 0 )
            return;
        
        // delete all tags
        if( count($tags) == 0 )
        {
            $tablename = self::tableName('Tag');
            
            // update count (-1) of those tags
            foreach( $current_tags as $tag )
			{
				DB::update($tablename)
					->set(array('count' => DB::expr('count - 1')))
					->where('name', '=', $tag)
					->execute();
			}
            
            return Record::deleteWhere( 'PageTag', 'page_id = :page_id', array(':page_id' => $this->id) );
        }
        else
        {
            $old_tags = array_diff($current_tags, $tags);
            $new_tags = array_diff($tags, $current_tags);
            
            // insert all tags in the tag table and then populate the page_tag table
            foreach( $new_tags as $index => $tag_name )
            {
                if ( !empty($tag_name) )
                {
                    // try to get it from tag list, if not we add it to the list
                    if ( ! ( $tag = Record::findOneFrom('Tag', 'name = :name', array(':name' => $tag_name)) ) )
					{
                        $tag = new Tag(array('name' => trim($tag_name)));
					}
                    
                    $tag->count++;
                    $tag->save();
                    
                    // create the relation between the page and the tag
                    $tag = new PageTag( array('page_id' => $this->id, 'tag_id' => $tag->id) );
                    $tag->save();
                }
            }
            
            // remove all old tag
            foreach( $old_tags as $index => $tag_name )
            {
                // get the id of the tag
                $tag = Record::findOneFrom('Tag', 'name = :name', array(':name' => $tag_name));
                Record::deleteWhere('PageTag', 'page_id = :page_id AND tag_id = :tag_id', array(':page_id' => $this->id, ':tag_id' => $tag->id));
                $tag->count--;
                $tag->save();
            }
        }
    }
    
    public static function find($args = null)
    {
        // Collect attributes...
        $where    = isset($args['where']) ? trim($args['where']) : '';
        $order_by = isset($args['order']) ? trim($args['order']) : '';
        $offset   = isset($args['offset']) ? (int) $args['offset'] : 0;
        $limit    = isset($args['limit']) ? (int) $args['limit'] : 0;
        
        // Prepare query parts
        $where_string = empty($where) ? '' : "WHERE $where";
        $order_by_string = empty($order_by) ? '' : "ORDER BY $order_by";
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';
        
        $tablename = self::tableName('Page');
        $tablename_user = self::tableName('User');
        
        // Prepare SQL
        $sql = "SELECT page.*, creator.name AS created_by_name, updator.name AS updated_by_name FROM $tablename AS page
               LEFT JOIN $tablename_user AS creator ON page.created_by_id = creator.id
               LEFT JOIN $tablename_user AS updator ON page.updated_by_id = updator.id
               $where_string $order_by_string $limit_string";
		
		$query = DB::query(Database::SELECT, $sql)
			->as_object(__CLASS__)
			->execute();

        // Run!
        if ($limit == 1)
        {
            return $query->current();
        }
        else
        {
            return $query->as_array('id');
        }
    }
    
    public static function findAll($args = null)
    {
        return self::find($args);
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => 'page.id='.(int)$id,
            'limit' => 1
        ));
    }
	
	public static function findAllLike($query)
	{
		$query = mysql_escape_string($query);
		
		$childrens = Record::findAllFrom(__CLASS__, 'LOWER(title) LIKE LOWER("%'.$query.'%") OR slug LIKE "%'.$query.'%" OR breadcrumb LIKE "%'.$query.'%" OR keywords LIKE "%'.$query.'%" OR description LIKE "%'.$query.'%" OR published_on LIKE "%'.$query.'%" OR created_on LIKE "%'.$query.'%"');
		
		return $childrens;
	}
    
    public static function childrenOf($id)
    {
        return self::find(array('where' => 'parent_id='.$id, 'order' => 'position DESC, page.created_on DESC'));
    }
    
    public static function hasChildren($id)
    {
        return (boolean) self::countFrom('Page', 'parent_id = '.(int)$id);
    }
    
    public static function cloneTree($page, $parent_id)
    {
        /* This will hold new id of SYSPATH of cloned tree. */
        static $new_SYSPATH_id = FALSE;
        
        /* Clone passed in page. */
        $clone = Record::findByIdFrom('Page', $page->id);
		
        $clone->parent_id = (int)$parent_id;
        $clone->id        = null;
        $clone->title    .= ' (copy)';
        $clone->slug     .= '-copy';
        
		$clone->save();
        
        /* Also clone the page parts. */
        $page_part = PagePart::findByPageId($page->id);
		
        if (count($page_part))
		{
            foreach( $page_part as $part )
			{
                $part->page_id = $clone->id;
                $part->id = null;
                $part->save();
            }
        }
        
        /* This gets set only once even when called recursively. */
        if ( !$new_SYSPATH_id )
            $new_SYSPATH_id = $clone->id;

        /* Clone and update childrens parent_id to clones new id. */
        if ( Page::hasChildren($page->id) )
		{
            foreach( Page::childrenOf($page->id) as $child )
                Page::cloneTree($child, $clone->id);
        }
        
        return $new_SYSPATH_id;
    }
	
	public static function deleteByParentId( $parent_id )
	{
		$pages = self::findAllFrom('Page', 'parent_id = ' . $parent_id);
		
		$result = true;
		
		foreach ($pages as $page)
		{
			if ( !$page->delete())
			{
				$result = FALSE;
			}
		}
		
		return $result;
	}
	
	public function getPermissions()
	{
		if (empty($this->id))
		{
			return array('administrator', 'developer', 'editor');
		}
			
		static $permissions = array();
		
		if (empty($permissions[$this->id]))
		{
			$sql = 'SELECT
				permission.id,
				permission.name
			FROM (
				'.TABLE_PREFIX.'page_permission AS page_permission
			LEFT JOIN
				'.TABLE_PREFIX.'permission AS permission
			ON
				page_permission.permission_id = permission.id
			)
			WHERE
				page_permission.page_id = '. $this->id;
			
			$query = DB::query(Database::SELECT, $sql)
				->as_object()
				->execute();
			
			if( ! $query )
			{
				return array();
			}

			$permissions[$this->id] = array('administrator');
			
			foreach ( $query as $object )
			{
				$permissions[$this->id][$object->id] = $object->name;
			}
        }
		
        return $permissions[$this->id];
	}
	
	// Save page permissions
	public function savePermissions( $permissions )
	{		
		// get permissions that already stored in database		
		$perms_in_table = DB::select('permission.name')
			->from( 'page_permission')
			->join('permission', 'left')
				->on('page_permission.permission_id', '=', 'permission.id')
			->where( 'page_id', '=', $this->id )
			->execute()
			->as_array(NULL, 'name');

		$new_perms = array_diff($permissions, $perms_in_table);
		$del_perms = array_diff($perms_in_table, $permissions);
		
		// add new ralates to page_permission
		foreach ($new_perms as $permission_name)
		{
			$select = DB::select('id')->from('permission')
				->where('name', '=', $permission_name);

			DB::insert('page_permission')
				->columns(array('page_id', 'permission_id'))
				->values(array($this->id, $select))
				->execute();
		}
		
		// remove old relatives from page_permission
		foreach ($del_perms as $permission_name)
		{
			$select = DB::select('id')->from('permission')
				->where('name', '=', $permission_name);

			DB::delete('page_permission')
				->where('page_id', '=', $this->id)
				->where('permission_id', '=', $select)
				->execute();
		}
	}
    
} // end Page class