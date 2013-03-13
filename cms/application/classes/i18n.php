<?php defined('SYSPATH') OR die('No direct script access.');

class I18n extends Kohana_I18n {
	
	public static function load($lang)
	{
		$table = parent::load($lang);
		
		$filename = CMSPATH . FileSystem::normalize_path('media/js/i18n/'.$lang.'.js');
		
		if( ! file_exists($filename) 
				OR ( file_exists($filename) AND (time() - filemtime($filename)) > Date::DAY))
		{
			if (Kohana::$profiling === TRUE AND class_exists('Profiler', FALSE))
			{
				// Start a new benchmark
				$benchmark = Profiler::start('i18n', 'Generate file for lang - ' . $lang);
			}
			
			// Create the log file
			file_put_contents($filename, '// Auto generated i18n lang file for lang '. $lang, FILE_APPEND);
			file_put_contents($filename, 'cms.addTranslation(' . json_encode($table) . ');');
	
			// Allow anyone to write to log files
			chmod($filename, 0666);
			
			if (isset($benchmark))
			{
				// Stop the benchmark
				Profiler::stop($benchmark);
			}
		}
		
		return $table;
	}
	
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