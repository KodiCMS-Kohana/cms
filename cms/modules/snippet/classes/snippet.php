<?php defined('SYSPATH') or die('No direct access allowed.');

class Snippet {
	
	public static function render($snippet_name, $vars = NULL, $cache_lifetime = NULL, $cache_by_uri = FALSE, $i18n = NULL)
	{
		$snippet = new Model_File_Snippet($snippet_name);

		if( ! $snippet->is_exists() )
		{
			return NULL;
		}
		
		if( $cache_lifetime !== NULL AND ! Fragment::load( self::_cache_key($snippet_name, $cache_by_uri), (int) $cache_lifetime, $i18n ))
		{
			echo View_Front::factory($snippet->get_file(), $vars);

			Fragment::save();
		}
		else if ($cache_lifetime === NULL)
		{
			echo View_Front::factory($snippet->get_file(), $vars);
		}
	}
	
	protected static function _cache_key($snippet_name, $cache_by_uri)
	{
		if($cache_by_uri !== FALSE)
		{
			$snippet_name .= Request::current()->uri();
		}
		
		return 'Snippet::' . $snippet_name;
	}
}