<?php defined('SYSPATH') OR die('No direct script access.');

class I18n extends Kohana_I18n {
	
	/**
	 * 
	 * @return string
	 */
	public static function detect_lang()
	{
		$browser_langs = array_keys(Request::accept_lang());		
		return array_shift($browser_langs);
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function available_langs()
	{
		$locale_names = Kohana::$config->load('locales');

		$langs = array('en' => __($locale_names->get('en')));
		
		$paths = array(APPPATH);

		if( defined( 'PLUGPATH' ))
		{
			$paths[] = PLUGPATH;
		}

		if ($files = Kohana::list_files('i18n', $paths))
		{
			foreach ($files as $file)
			{
				$lang = pathinfo($file, PATHINFO_FILENAME);
				
				if(!in_array($lang, $langs))
				{
					$langs[$lang] = __($locale_names->get($lang));
				}
			}
		}
	
		return $langs;
	}

}