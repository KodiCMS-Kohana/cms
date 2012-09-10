<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plural inflection for languages, according to CLDR Language Plural Rules
 * Reference CLDR Version 1.8.1 (2010-04-30 23:05:14 GMT)
 * @see http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html
 * @see http://unicode.org/repos/cldr/trunk/common/supplemental/plurals.xml
 * 
 * @package		I18n_Plural
 * @author		Korney Czukowski
 * @copyright	(c) 2010 Korney Czukowski
 * @license		http://kohanaphp.com/license
 */
class I18n_Plural
{
	/**
	 * Plural rules classes instances
	 * @var array
	 */
	protected static $_instances = array();

	/**
	 * Returns translation of a string. If no translation exists, the original string will be
	 * returned. No parameters are replaced.
	 *
	 *     $hello = I18n_Plural::get('Hello, my name is :name and I have :count friend.', 10);
	 *     // 'Hello, my name is :name and I have :count friends.'
	 *
	 * @param string $string
	 * @param mixed $count
	 * @param string $lang
	 * @return string
	 */
	public static function get($string, $count = 0, $lang = NULL)
	{
		// Get the translation form key
		$form = I18n_Plural::instance(I18n::$lang)->get_category($count);
		// Return the translation for that form
		return I18n_Form::get($string, $form, $lang);
	}

	/**
	 * Returns class instance, that handles plural inflection for the given language
	 * @param string $lang
	 * @return I18n_Plural_Rules
	 */
	public static function instance($lang)
	{
		if (isset(self::$_instances[$lang]))
		{
			return self::$_instances[$lang];
		}

		// Get language code prefix
		$parts = explode('-', $lang, 2);

		self::$_instances[$lang] = self::_get_class($parts[0]);
		return self::$_instances[$lang];
	}

	/**
	 * Chooses inflection class to use according to CLDR plural rules
	 * @param string $prefix
	 * @return I18n_Plural_Rules
	 */
	private static function _get_class($prefix)
	{
		// Choose class
		if (in_array($prefix, array(
			'bem', 'brx', 'da', 'de', 'el', 'en', 'eo', 'es', 'et', 'fi', 'fo', 'gl', 'he', 'iw', 'it', 'nb',
			'nl', 'nn', 'no', 'sv', 'af', 'bg', 'bn', 'ca', 'eu', 'fur', 'fy', 'gu', 'ha', 'is', 'ku',
			'lb', 'ml', 'mr', 'nah', 'ne', 'om', 'or', 'pa', 'pap', 'ps', 'so', 'sq', 'sw', 'ta', 'te',
			'tk', 'ur', 'zu', 'mn', 'gsw', 'chr', 'rm', 'pt')))
		{
			return new I18n_Plural_One;
		}
		elseif (in_array($prefix, array('cs', 'sk')))
		{
			return new I18n_Plural_Czech;
		}
		elseif (in_array($prefix, array('ff', 'fr', 'kab')))
		{
			return new I18n_Plural_French;
		}
		elseif (in_array($prefix, array('hr', 'ru', 'sr', 'uk', 'be', 'bs', 'sh')))
		{
			return new I18n_Plural_Balkan;
		}
		elseif ($prefix == 'lv')
		{
			return new I18n_Plural_Latvian;
		}
		elseif ($prefix == 'lt')
		{
			return new I18n_Plural_Lithuanian;
		}
		elseif ($prefix == 'pl')
		{
			return new I18n_Plural_Polish;
		}
		elseif (in_array($prefix, array('ro', 'mo')))
		{
			return new I18n_Plural_Romanian;
		}
		elseif ($prefix == 'sl')
		{
			return new I18n_Plural_Slovenian;
		}
		elseif ($prefix == 'ar')
		{
			return new I18n_Plural_Arabic;
		}
		elseif ($prefix == 'mk')
		{
			return new I18n_Plural_Macedonian;
		}
		elseif ($prefix == 'cy')
		{
			return new I18n_Plural_Welsh;
		}
		elseif ($prefix == 'br')
		{
			return new I18n_Plural_Breton;
		}
		elseif ($prefix == 'lag')
		{
			return new I18n_Plural_Langi;
		}
		elseif ($prefix == 'shi')
		{
			return new I18n_Plural_Tachelhit;
		}
		elseif ($prefix == 'mt')
		{
			return new I18n_Plural_Maltese;
		}
		elseif (in_array($prefix, array('ga', 'se', 'sma', 'smi', 'smj', 'smn', 'sms')))
		{
			return new I18n_Plural_Two;
		}
		elseif (in_array($prefix, array('ak', 'am', 'bh', 'fil', 'tl', 'guw', 'hi', 'ln', 'mg', 'nso', 'ti', 'wa')))
		{
			return new I18n_Plural_Zero;
		}
		elseif (in_array($prefix, array(
			'az', 'bm', 'fa', 'ig', 'hu', 'ja', 'kde', 'kea', 'ko', 'my', 'ses', 'sg', 'to',
			'tr', 'vi', 'wo', 'yo', 'zh', 'bo', 'dz', 'id', 'jv', 'ka', 'km', 'kn', 'ms', 'th')))
		{
			return new I18n_Plural_None;
		}
		throw new Kohana_Exception('Unknown language prefix: :prefix.', array(':prefix' => $prefix));
	}
}