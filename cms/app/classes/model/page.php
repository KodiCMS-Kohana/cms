<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Model
 */
class Model_Page extends Record
{
    const TABLE_NAME = 'pages';
    
    const STATUS_DRAFT			= 1;
    const STATUS_REVIEWED		= 50;
    const STATUS_PUBLISHED		= 100;
    const STATUS_HIDDEN			= 101;

    const LOGIN_NOT_REQUIRED	= 0;
    const LOGIN_REQUIRED		= 1;
    const LOGIN_INHERIT			= 2;
	
	public function filters()
	{
		return array(
			'title' => array(
				array('integer')
			)
		);
	}

	public static function logins()
	{
		return array(
			self::LOGIN_NOT_REQUIRED => __('Not required'),
			self::LOGIN_REQUIRED => __('Required'),
			self::LOGIN_INHERIT => __('inherit')
		);
	}

	public static function statuses()
	{
		return array(
			self::STATUS_DRAFT => __('Draft'),
			self::STATUS_REVIEWED => __('Reviewed'),
			self::STATUS_PUBLISHED => __('Published'),
			self::STATUS_HIDDEN => __('Hidden')
		);
	}

	public function beforeInsert()
    {		
        $this->created_on = date('Y-m-d H:i:s');
        $this->created_by_id = AuthUser::getId();
        
        $this->updated_on = $this->created_on;
        $this->updated_by_id = $this->created_by_id;
        
        if ($this->status_id == Model_Page::STATUS_PUBLISHED)
		{
            $this->published_on = date('Y-m-d H:i:s');
		}
        
		if ($this->position == 0)
		{
			$last_position = DB::select(array(DB::expr('MAX(position)'), 'pos'))
				->from(self::TABLE_NAME)
				->where('parent_id', '=', $this->parent_id)
				->execute()
				->get('pos', 0);
			
			$this->position = ((int) $last_position) + 1;
		}
		
        return TRUE;
    }
	
	public function afterInsert()
	{
		$page = DB::select('id')
			->from(self::TABLE_NAME)
			->where('id', '<>', $this->id)
			->where('slug', '=', $this->slug)
			->where('parent_id', '=', $this->parent_id)
			->execute()
			->get('id');
		
		if ($page !== NULL)
		{
			DB::update(self::TABLE_NAME)
				->set(array(
					'slug' => DB::expr('CONCAT(slug, "-", id)')
				))
				->where('id', '=', $this->id)
				->where('parent_id', '=', $this->parent_id)
				->execute();
		}
		
		return TRUE;
	}
    
    public function beforeUpdate()
    {	
        if( empty($this->published_on) && $this->status_id == Model_Page::STATUS_PUBLISHED)
        {
            $this->published_on = date('Y-m-d H:i:s');
        }
        
        $this->updated_by_id = AuthUser::getId();
        $this->updated_on = date('Y-m-d H:i:s');
        
        return TRUE;
    }
	
	public function afterUpdate()
	{
		$cache = Cache::instance();
		$cache->delete_tag('pages');
		$cache->delete_tag('page_parts');
		$cache->delete_tag('page_tags');

		return $this->afterInsert();
	}
	
	public function beforeDelete()
	{
		// need to delete subpages
		self::deleteByParentId($this->id);

		return TRUE;
	}

    public function getUri()
    {
        $result = NULL;

        $parent = $this->findById($this->parent_id);
		
        if( $parent != NULL && $parent->slug != '' )
		{
            $result = $parent->getUri().'/'.$this->slug;
        }
        else
		{
            $result = $this->slug;
        }

        return $result;
    }
	
	public function getUrl()
	{
		$uri = $this->getUri();
		return URL::base(TRUE) 
			. $uri 
			. (!URL::check_suffix( $uri , '.') ? URL_SUFFIX : '');
	}

	public function getTags()
    {
        $tablename_page_tag = self::tableName('Model_Page_Tag');
        $tablename_tag = self::tableName('Model_Tag');
		
		return DB::select(array('tags.id', 'id'), array('tags.name', 'tag'))
			->from(array($tablename_page_tag, 'page_tags'))
			->join(array($tablename_tag, 'tags'), 'left')
				->on('page_tags.tag_id', '=', 'tags.id')
			->where('page_tags.page_id', '=', $this->id)
			->execute()
			->as_array('id', 'tag');
    }
    
    public function saveTags($tags)
    {
        if( is_string($tags) )
		{
            $tags = explode(Model_Tag::SEPARATOR, $tags);
		}
        
        $tags = array_unique(array_map('trim', $tags));
        
        $current_tags = $this->getTags();
        
        // no tag before! no tag now! ... nothing to do!
        if( count($tags) == 0 AND count($current_tags) == 0 )
		{
            return;
		}
        
        // delete all tags
        if( count($tags) == 0 )
        {
            // update count (-1) of those tags
            foreach( $current_tags as $tag )
			{
				DB::update(Model_Tag::tableName())
					->set(array('count' => DB::expr('count - 1')))
					->where('name', '=', $tag)
					->execute();
			}
            
            return Record::deleteWhere( 'Model_Page_Tag', array(
				'where' => array(array('page_id', '=', $this->id))));
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
					$tag = Record::findOneFrom('Model_Tag', array(
						'where' => array(array('name', '=', $tag_name))));
							
                    // try to get it from tag list, if not we add it to the list
                    if ( ! $tag);
					{
                        $tag = new Model_Tag(array('name' => trim($tag_name)));
					}
                    
                    $tag->count++;
                    $tag->save();
                    
                    // create the relation between the page and the tag
                    $tag = new Model_Page_Tag( array('page_id' => $this->id, 'tag_id' => $tag->id) );
                    $tag->save();
                }
            }
            
            // remove all old tag
            foreach( $old_tags as $index => $tag_name )
            {
                // get the id of the tag
                $tag = Record::findOneFrom('Model_Tag',
						array('where' => array(array('name', '=', $tag_name))));

                Record::deleteWhere('Model_Page_Tag', array(
					'where' => array(
						array('page_id', '=', $this->id),
						array('tag_id', '=', $tag->id)
					)));
	
                $tag->count--;
                $tag->save();
            }
        }
    }
    
    public static function find($clause = array())
    {
        $tablename = self::tableName('Model_Page');
        $tablename_user = self::tableName('User');
		
		$sql = DB::select('page.*')
			->select(array('author.name', 'created_by_name'))
			->select(array('updator.name', 'updated_by_name'))
			->from(array(Model_Page::tableName(), 'page'))
			->join(array(User::tableName(), 'author'), 'left')
				->on('author.id', '=', 'page.created_by_id')
			->join(array(User::tableName(), 'updator'), 'left')
				->on('updator.id', '=', 'page.updated_by_id');
        
        // Prepare SQL
        $sql = self::_conditions($sql, $clause);
		
		$query = $sql
			->as_object(__CLASS__)
			->execute();

        // Run!
        if (Arr::get($clause, 'limit') == 1)
        {
            return $query->current();
        }
        else
        {
            return $query->as_array('id');
        }
    }
    
    public static function findAll($clause = array())
    {
        return self::find($clause);
    }
    
    public static function findById($id)
    {
        return self::find(array(
            'where' => array(array('page.id', '=', (int) $id)),
			'limit' => 1
        ));
    }
	
	public static function findAllLike($query)
	{
		$childrens = Record::findAllFrom(__CLASS__, array(
			'or_where' => array(
				array(DB::expr('LOWER(title)'), 'like', '%:query%'),
				array('slug', 'like', '%:query%'),
				array('breadcrumb', 'like', '%:query%'),
				array('keywords', 'like', '%:query%'),
				array('description', 'like', '%:query%'),
				array('published_on', 'like', '%:query%'),
				array('created_on', 'like', '%:query%'),
			)
		), 
		array(
			':query' => DB::expr($query)
		));
		
		return $childrens;
	}
    
    public static function childrenOf($id, $clause = array())
    {
		$default_clause = array(
			'where' => array(array('parent_id', '=', $id)),
			'order_by' => array(
				array('position', 'desc'),
				array('page.created_on', 'desc')
			));
		
		if( is_array( $clause ))
		{
			$default_clause = Arr::merge($default_clause, $clause);
		}

        return self::find($default_clause);
    }
    
    public static function hasChildren($id)
    {
        return (boolean) self::countFrom('Model_Page', array(
			'where' => array(array('parent_id', '=', (int) $id))));
    }
	
	public static function deleteByParentId( $parent_id )
	{
		$pages = self::findAllFrom('Model_Page', array(
			'where' => array(array('parent_id', '=', (int) $parent_id))));
		
		$result = TRUE;
		
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
			$query = DB::select('role.id', 'role.name')
				->from(array(Model_Page_Role::tableName(), 'page_roles'))
				->join(array(Model_Permission::tableName(), 'role'), 'left')
					->on('page_roles.role_id', '=','role.id')
				->where('page_roles.page_id', '=', $this->id)
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
	
	// Save Model_Page permissions
	public function savePermissions( $permissions )
	{
		if(!is_array( $permissions ))
		{
			$permissions = array();
		}

		// get permissions that already stored in database		
		$perms_in_table = DB::select('role.name')
			->from(array(Model_Page_Role::tableName(), 'page_roles'))
			->join(array(Model_Permission::tableName(), 'role'), 'left')
				->on('page_roles.role_id', '=', 'role.id')
			->where( 'page_roles.page_id', '=', $this->id )
			->execute()
			->as_array(NULL, 'name');

		$new_perms = array_diff($permissions, $perms_in_table);
		$del_perms = array_diff($perms_in_table, $permissions);
		
		// add new ralates to page_permission
		foreach ($new_perms as $permission_name)
		{
			$select = DB::select('id')
				->from(Model_Permission::tableName())
				->where('name', '=', $permission_name);

			DB::insert(Model_Page_Role::tableName())
				->columns(array('page_id', 'role_id'))
				->values(array($this->id, $select))
				->execute();
		}
		
		// remove old relatives from page_permission
		foreach ($del_perms as $permission_name)
		{
			$select = DB::select('id')
				->from(Model_Permission::tableName())
				->where('name', '=', $permission_name);

			DB::delete(Model_Page_Role::tableName())
				->where('page_id', '=', $this->id)
				->where('role_id', '=', $select)
				->execute();
		}
	}
    
} // end Model_Page class