<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_Page_Permission extends Record
{
    const TABLE_NAME = 'page_roles';
	
	
	public static function find_by_page($page_id)
	{
		$roles = DB::select('role.id', 'role.name')
			->from(array(Model_Page_Permission::tableName(), 'page_roles'))
			->join(array(Model_Permission::tableName(), 'role'), 'left')
				->on('page_roles.role_id', '=','role.id')
			->where('page_roles.page_id', '=', (int) $page_id )
			->execute()
			->as_array('id', 'name');
		
		if( !in_array('administrator', $roles))
		{
			$roles[] = 'administrator';
		}
		
		return $roles;
	}
	
	public static function save_by_page($page_id, $permissions)
	{
		if( ! is_array( $permissions ) )
		{
			$permissions = array();
		}

		// get permissions that already stored in database		
		$perms_in_table = array_keys( self::find_by_page($page_id) );

		$new_perms = array_diff($permissions, $perms_in_table);
		$del_perms = array_diff($perms_in_table, $permissions);
		
		// add new ralates to page_permission
		foreach ($new_perms as $id)
		{
			DB::insert(Model_Page_Permission::tableName())
				->columns(array('page_id', 'role_id'))
				->values(array($page_id, (int) $id))
				->execute();
		}
		
		// remove old relatives from page_permission
		foreach ($del_perms as $id)
		{
			DB::delete(Model_Page_Permission::tableName())
				->where('page_id', '=', $page_id)
				->where('role_id', '=', (int) $id)
				->execute();
		}
	}

} // end Model_Page_Permission class