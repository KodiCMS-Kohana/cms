<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

define('CACHE_DYNAMIC_SYSPATH', PLGPATH.'cache'.DIRECTORY_SEPARATOR.'dynamic'.DIRECTORY_SEPARATOR);
define('CACHE_STATIC_SYSPATH', PLGPATH.'cache'.DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR);


/*
* Handler for page_delete observer event
*/
function cache_page_delete_handler($page)
{
	$uri = $page->getUri();
	
	$file_name = md5($uri) . EXT;
	$file_path = CACHE_DYNAMIC_SYSPATH.$file_name;
	
	if (file_exists($file_path))
		@unlink($file_path);
} // end cache_page_delete_handler


/*
* Handler for view_page_edit_options observer event
*/
function cache_view_page_edit_options_handler($page)
{
	if ( AuthUser::hasPermission(array('admin','developer')) )
	{
		$page_caching = false;
	
		if (isset($page->id))
		{
			$conn = Record::getConnection();
			
			$sql = 'SELECT page_id FROM '.TABLE_PREFIX.'cache_page WHERE page_id=?';
			$sth = $conn->prepare($sql);
			$sth->execute(array($page->id));
			
			if ($sth->fetch())
				$page_caching = true;
		}
		else
		{
			$page_caching = true;
		}
		
		echo '<p id="CachePage"><input id="CachePageCheckbox" type="checkbox" name="cache[cache_page]" value="yes" '. ($page_caching === true ? 'checked': '') .' /> <label for="CachePageCheckbox">'.__('Cache this page').'</label><p>';
	}
} // end cache_view_page_edit_options_handler


/*
* Handler for page_edit_after_save observer event
*/
function cache_page_edit_after_save_handler($page)
{
	$conn = Record::getConnection();
	
	$uri = $page->getUri();
	
	if ($uri == '')
		$uri = '/';
	
	$file_name = md5($uri) . EXT;
	
	if (isset($_POST['cache']['cache_page']) && $_POST['cache']['cache_page'] == 'yes')
	{		
		$sql = 'INSERT IGNORE '.TABLE_PREFIX.'cache_page(page_id, cache_id) VALUES(?, ?)';
		$sth = $conn->prepare($sql);
		$sth->execute(array($page->id, md5($uri)));
		
		$file_path = CACHE_STATIC_SYSPATH.$file_name;
		
		// Remove cache
		$remove_static = Plugin::getSetting('cache_remove_static', 'cache');
		
		if ($remove_static == 'yes')
		{
			$dir = new DirectoryIterator(CACHE_STATIC_SYSPATH);
			
			foreach ($dir as $file)
			{
				if (!$file->isDot() && $file->isFile())
					unlink($file->getPathname());
			}
		}
		else
		{
			if (file_exists($file_path))
				unlink($file_path);
		}
	}
	else // remove page from cache
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'cache_page WHERE page_id=?';
		$sth = $conn->prepare($sql);
		$sth->execute(array($page->id));
	}

	$dynamic_file_path = CACHE_DYNAMIC_SYSPATH.$file_name;
	
	if (file_exists($dynamic_file_path))
		unlink($dynamic_file_path);	
} // end cache_page_edit_after_save_handler


// Get settings
$cache_dynamic = Plugins::getSetting('cache_dynamic', 'cache');

// Observer
if ($cache_dynamic == 'yes')
{
	Observer::observe('page_edit_before_save', 'cache_page_delete_handler');
	Observer::observe('page_delete', 'cache_page_delete_handler');		
}

Observer::observe('view_page_edit_options', 'cache_view_page_edit_options_handler');
Observer::observe('page_add_after_save', 'cache_page_edit_after_save_handler');
Observer::observe('page_edit_after_save', 'cache_page_edit_after_save_handler');