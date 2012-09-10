<?php defined('SYSPATH') or die('No direct script access.');
/**
 * I18n_Core class
 * Extends Kohana_I18n class with get() and load() functions, that does recursive merging of language files
 * and use Arr::path to get values.
 *
 * Note: Create 'class I18n extends I18n_Core{}' in your application
 *
 * @package		I18n_Core
 * @author		Korney Czukowski
 * @copyright	(c) 2010 Korney Czukowski
 * @license		http://kohanaframework.org/license
 */
class I18n_Core extends Kohana_I18n
{
	/**
	 * Returns translation of a string. If no translation exists, the original
	 * string will be returned. No parameters are replaced.
	 *
	 *     $hello = I18n::get('Hello friends, my name is :name');
	 *
	 * @param   string   text to translate
	 * @param   string   target language
	 * @return  string
	 */
	public static function get($string, $lang = NULL)
	{
		if ( ! $lang)
		{
			// Use the global target language
			$lang = I18n::$lang;
		}

		// Load the translation table for this language
		$table = I18n::load($lang);

		// Return the translated string if it exists
		if (isset($table[$string]))
		{
			return $table[$string];
		}
		elseif (($translation = Arr::path($table, $string)) !== NULL)
		{
			return $translation;
		}
		return $string;
	}

	/**
	 * Returns the translation table for a given language.
	 *
	 *     // Get all defined Spanish messages
	 *     $messages = I18n::load('es-es');
	 *
	 * @param   string   language to load
	 * @return  array
	 */
	public static function load($lang)
	{
		if (isset(I18n::$_cache[$lang]))
		{
			return I18n::$_cache[$lang];
		}

		// New translation table
		$table = array();

		// Split the language: language, region, locale, etc
		$parts = explode('-', $lang);

		do
		{
			// Create a path for this set of parts
			$path = implode(DIRECTORY_SEPARATOR, $parts);

			if ($files = Kohana::find_file('i18n', $path, NULL, TRUE))
			{
				$t = array();
				foreach ($files as $file)
				{
					// Merge the language strings into the sub table
					$t = i18n::merge_arrays($t, Kohana::load($file));
				}

				// Append the sub table, preventing less specific language
				// files from overloading more specific files
				$table += $t;
			}

			// Remove the last part
			array_pop($parts);
		}
		while ($parts);

		// Cache the translation table locally
		return I18n::$_cache[$lang] = $table;
	}
	
	// Thank you, http://www.php.net/manual/en/function.array-merge-recursive.php#102379
	private static function merge_arrays($array1, $array2)
	{
		foreach($array2 as $key => $value)
		{
			if(array_key_exists($key, $array1) && is_array($value))
			{
				$array1[$key] = i18n::merge_arrays($array1[$key], $array2[$key]);
			}	
			else
			{
				$array1[$key] = $value;
			}		
		}
		
		return $array1;
	}
}