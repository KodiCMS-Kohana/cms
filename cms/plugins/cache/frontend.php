<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

define('CACHE_DYNAMIC_SYSPATH', PLGPATH.'cache'.DIRECTORY_SEPARATOR.'dynamic'.DIRECTORY_SEPARATOR);
define('CACHE_STATIC_SYSPATH', PLGPATH.'cache'.DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR);


/*
* Handler for frontpage_byslug_found observer event
*/
function cache_frontpage_byslug_found_handler($page)
{
	$uri = trim($page->url, '/');
	
	if ($uri == '')
		$uri = '/';
	
	$file_name = md5($uri) . EXT;
	$file_path = CACHE_DYNAMIC_SYSPATH.$file_name;
	
	$serialized_content = '<'.'?php die; ?>' . serialize($page);
	
	file_put_contents($file_path, $serialized_content);
} // end cache_frontpage_byslug_found_handler


/*
* Handler for frontpage_byslug_before_found observer event
*/
function cache_frontpage_byslug_before_found_handler($page, $slug, $parent)
{
	if ($parent)
		$uri = trim($parent->url .'/'. $slug, '/');
	else
		$uri = '/';
	
	$file_name = md5($uri) . EXT;
	$file_path = CACHE_DYNAMIC_SYSPATH.$file_name;
	
	if (file_exists($file_path))
	{
		$cache_lifetime = (int) Plugins::getSetting('cache_lifetime', 'cache');
		
		if (time() - filemtime($file_path) < $cache_lifetime)
		{
			// Read cache file, unserialize and execute
			$serialized_content = substr(file_get_contents($file_path), 13);
			
			$page = unserialize($serialized_content);
		}
	}
} // end cache_frontpage_byslug_before_found_handler


/*
* Handler for page_requested observer event
*/
function cache_frontpage_requested_handler($uri)
{
	$uri = trim($uri, '/');
	
	if ($uri == '')
		$uri = '/';
	
	$conn = Record::getConnection();
	
	$sql = 'SELECT page_id FROM '.TABLE_PREFIX.'cache_page WHERE cache_id=?';
	$sth = $conn->prepare($sql);
	$sth->execute(array(md5($uri)));
	
	if ($sth->fetch())
	{
		$file_name = md5($uri) . EXT;
		$file_path = CACHE_STATIC_SYSPATH.$file_name;
		
		if (file_exists($file_path))
		{
			$cache_lifetime = (int) Plugins::getSetting('cache_lifetime', 'cache');
			
			if (time() - filemtime($file_path) < $cache_lifetime)
			{
				$page_content = substr(file_get_contents($file_path), 13);
				
				echo $page_content;
				
				echo '<!-- from cache. time: '. execution_time() .'-->';
				die;
			}
		}
	}
} // end cache_page_requested_handler


/*
* Handler for frontpage_found observer event
*/
function cache_frontpage_found_handler($page)
{
	$uri = trim($page->url);
	
	if ($uri == '')
		$uri = '/';
	
	$conn = Record::getConnection();
	
	$sql = 'SELECT page_id FROM '.TABLE_PREFIX.'cache_page WHERE cache_id=?';
	$sth = $conn->prepare($sql);
	$sth->execute(array(md5($uri)));
	
	if ($sth->fetch())
	{
		$file_name = md5($uri) . EXT;
		$file_path = CACHE_STATIC_SYSPATH.$file_name;
		
		ob_start();
		$page->display();
		$page_content = ob_get_contents();
		ob_end_flush();
		
		$page_content = '<'.'?php die; ?>' . $page_content;
		
		file_put_contents($file_path, $page_content);
		
		die;
	}
} // end cache_frontpage_found_handler


// Get cache type
$cache_dynamic = Plugins::getSetting('cache_dynamic', 'cache');
$cache_static = Plugins::getSetting('cache_static', 'cache');

// Observer
if ($cache_dynamic == 'yes')
{
	Observer::observe('frontpage_byslug_before_found', 'cache_frontpage_byslug_before_found_handler');
	Observer::observe('frontpage_byslug_found', 'cache_frontpage_byslug_found_handler');
}

if ($cache_static == 'yes')
{
	Observer::observe('frontpage_requested', 'cache_frontpage_requested_handler');
	Observer::observe('frontpage_found', 'cache_frontpage_found_handler');
}