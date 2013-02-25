<?php defined('SYSPATH') or die('No direct access allowed.');

class Snippet {
	
	public static function render($snippet_name, $vars = NULL, $cache_lifetime = NULL, $cache_by_uri = FALSE, $i18n = NULL)
	{
		$view = Snippet::get($snippet_name, $vars);
		$snippet = new Model_File_Snippet($snippet_name);
		
		if( $view === NULL )
		{
			return NULL;
		}

		
		if( $cache_lifetime !== NULL AND ! Fragment::load( self::_cache_key($snippet_name, $cache_by_uri), (int) $cache_lifetime, $i18n ))
		{
			echo $view;

			Fragment::save();
		}
		else if ($cache_lifetime === NULL)
		{
			echo $view;
		}
	}
	
	public static function get($snippet_name, $vars = NULL)
	{
		$snippet = new Model_File_Snippet($snippet_name);
		
		if( ! $snippet->is_exists() )
		{
			return NULL;
		}
		
		$view = View_Front::factory($snippet->get_file(), $vars);
		
		if(isset($view->page_object))
		{
			$view->page = $view->page_object;
		}
		
		return $view;
	}

	protected static function _cache_key($snippet_name, $cache_by_uri = FALSE)
	{
		if($cache_by_uri !== FALSE)
		{
			$snippet_name .= Request::current()->uri() . serialize(Request::current()->query());
		}
		
		return 'Snippet::' . $snippet_name;
	}
}