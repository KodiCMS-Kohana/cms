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
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @since Flexo version 0.1
 */
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
			// set page position (testing)
			$sql = 'SELECT MAX(page.position) AS pos FROM '.TABLE_PREFIX.'page AS page WHERE parent_id=' . $this->parent_id;
			$sth = self::$__CONN__->prepare($sql);
			$sth->execute();
			
			if ($row = $sth->fetch())
				$this->position = ((int) $row['pos']) + 1;
		}
		
        return true;
    }
	
	public function afterInsert()
	{
		$sql = 'SELECT page.id FROM '.TABLE_PREFIX.'page AS page WHERE page.id <> '.$this->id.' AND page.slug = "'.$this->slug.'" AND page.parent_id = '.$this->parent_id;
		$sth = self::$__CONN__->prepare($sql);
		
		$sth->execute();
		
		if ($sth->fetch())
		{
			$sql = 'UPDATE '.TABLE_PREFIX.'page AS page SET page.slug = CONCAT(page.slug, "-", page.id) WHERE page.id = '.$this->id.' AND page.parent_id = '.$this->parent_id;
			self::$__CONN__->exec($sql);
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
		
		// remove all info about permissions
		self::$__CONN__->query('DELETE FROM '.TABLE_PREFIX.'page_permission WHERE page_id='.$this->id);
		
		// remove indormation about tags
		self::$__CONN__->query('DELETE FROM '.TABLE_PREFIX.'page_tag WHERE page_id='.$this->id);
		
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
        $tablename_page_tag = self::tableNameFromClassName('PageTag');
        $tablename_tag = self::tableNameFromClassName('Tag');
        
        $sql = "SELECT tag.id AS id, tag.name AS tag FROM $tablename_page_tag AS page_tag, $tablename_tag AS tag ".
               "WHERE page_tag.page_id={$this->id} AND page_tag.tag_id = tag.id";
        
        if( !( $stmt = self::$__CONN__->prepare($sql) ) )
            return array();
            
        $stmt->execute();
        
        // Run!
        $tags = array();
        while( $object = $stmt->fetchObject() )
             $tags[$object->id] = $object->tag;
        
        return $tags;
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
            $tablename = self::tableNameFromClassName('Tag');
            
            // update count (-1) of those tags
            foreach( $current_tags as $tag )
                self::$__CONN__->exec("UPDATE $tablename SET count = count - 1 WHERE name = '$tag'");
            
            return Record::deleteWhere( 'PageTag', 'page_id=?', array($this->id) );
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
                    if ( ! ( $tag = Record::findOneFrom('Tag', 'name=?', array($tag_name)) ) )
                        $tag = new Tag(array('name' => trim($tag_name)));
                    
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
                $tag = Record::findOneFrom('Tag', 'name=?', array($tag_name));
                Record::deleteWhere('PageTag', 'page_id=? AND tag_id=?', array($this->id, $tag->id));
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
        
        $tablename = self::tableNameFromClassName('Page');
        $tablename_user = self::tableNameFromClassName('User');
        
        // Prepare SQL
        $sql = "SELECT page.*, creator.name AS created_by_name, updator.name AS updated_by_name FROM $tablename AS page
               LEFT JOIN $tablename_user AS creator ON page.created_by_id = creator.id
               LEFT JOIN $tablename_user AS updator ON page.updated_by_id = updator.id
               $where_string $order_by_string $limit_string";
        
        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute();
        
        // Run!
        if ($limit == 1)
        {
            return $stmt->fetchObject('Page');
        }
        else
        {
            $objects = array();
            while ($object = $stmt->fetchObject('Page'))
                $objects[] = $object;
            
            return $objects;
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
        /* This will hold new id of root of cloned tree. */
        static $new_root_id = false;
        
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
        if ( !$new_root_id )
            $new_root_id = $clone->id;

        /* Clone and update childrens parent_id to clones new id. */
        if ( Page::hasChildren($page->id) )
		{
            foreach( Page::childrenOf($page->id) as $child )
                Page::cloneTree($child, $clone->id);
        }
        
        return $new_root_id;
    }
	
	public static function deleteByParentId( $parent_id )
	{
		$pages = self::findAllFrom('Page', 'parent_id = ' . $parent_id);
		
		$result = true;
		
		foreach ($pages as $page)
		{
			if ( !$page->delete())
				$result = false;
		}
		
		return $result;
	}
	
	public function getPermissions()
	{
		if (empty($this->id))
			return array('administrator', 'developer', 'editor');
			
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
			
			if( !( $stmt = self::$__CONN__->prepare($sql) ) )
				return array();
			
			$stmt->execute();
			
			$permissions[$this->id] = array('administrator');
			
			while( $object = $stmt->fetchObject() )
				$permissions[$this->id][$object->id] = $object->name;
        }
		
        return $permissions[$this->id];
	}
	
	// Save page permissions
	public function savePermissions( $permissions )
	{		
		// get permissions that already stored in database
		$sql = '
		SELECT
			permission.name
		FROM (
			'.TABLE_PREFIX.'page_permission AS page_permission
		LEFT JOIN
			'.TABLE_PREFIX.'permission AS permission
		ON
			page_permission.permission_id=permission.id
		)
		WHERE
			page_id=' . $this->id;
		$perms_in_table = self::$__CONN__->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);
		
		$new_perms = array_diff($permissions, $perms_in_table);
		$del_perms = array_diff($perms_in_table, $permissions);
		
		// add new ralates to page_permission
		foreach ($new_perms as $permission_name)
		{
			$sql = 'INSERT INTO '.TABLE_PREFIX.'page_permission(page_id, permission_id) VALUES('.$this->id.', (SELECT permission.id FROM '.TABLE_PREFIX.'permission AS permission WHERE permission.name='. self::$__CONN__->quote($permission_name).'))';
			self::$__CONN__->query($sql);
		}
		
		// remove old relatives from page_permission
		foreach ($del_perms as $permission_name)
		{
			$sql = 'DELETE FROM '.TABLE_PREFIX.'page_permission WHERE page_id='.$this->id.' AND permission_id=(SELECT permission.id FROM '.TABLE_PREFIX.'permission AS permission WHERE permission.name='. self::$__CONN__->quote($permission_name).')';
			self::$__CONN__->query($sql);
		}
	}
    
} // end Page class