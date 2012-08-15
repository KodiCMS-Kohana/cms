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
 * @subpackage helpers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

/**
 * 
 */
defined('I18N_PATH') or define('I18N_PATH', APP_PATH.DIRECTORY_SEPARATOR.'i18n');
//define('DEFAULT_LOCALE', 'en');

/**
 * This function is as flexible as possible, you can choose your own pattern for variables in
 * the string.
 *
 * Examples of variables are: ':var_name', '#var_name', '{varname}',
 * '%varname', '%varname%', 'VARNAME', etc...
 *
 * <code>
 * return = array('hello world!' => 'bonjour le monde!',
 *                'user ":user" is logged in' => 'l\'utilisateur ":user" est connecté',
 *                'Posted by %user% on %month% %day% %year% at %time%' => 'Publié par %user% le %day% %month% %year% à %time%'
 *               );
 *
 * __('hello world!'); // bonjour le monde!
 * __('user ":user" is logged in', array(':user' => $user)); // l'utilisateur "demo" est connecté
 * __('Posted by %user% on %month% %day% %year% at %time%', array(
 *      '%user%' => $user, 
 *      '%month%' => __($month), 
 *      '%day%' => $day, 
 *      '%year%' => $year, 
 *      '%time%' => $time)); // Publié par demo le 3 janvier 2006 à 19:30
 * </code>
 */
function __($string, $args=null)
{
	//if (I18n::getLocale() != DEFAULT_LOCALE)
		$string = I18n::getText($string);

	if ($args === null) return $string;
	
	return strtr($string, $args);
}

/**
 * I18n : Internationalisation function and class
 *
 */
class I18n 
{
	private static $locale = 'en';
	private static $array = array();
	
	// ISO 639 1
	public static $locale_names = array(
		'aa' => 'Afar',
		'ab' => 'Abkhazian',
		'ae' => 'Avestan',
		'af' => 'Afrikaans',
		'ak' => 'Akan',
		'am' => 'Amharic',
		'an' => 'Aragonese',
		'ar' => 'Arabic',
		'as' => 'Assamese',
		'av' => 'Avaric',
		'ay' => 'Aymara',
		'az' => 'Azerbaijani',
		'ba' => 'Bashkir',
		'be' => 'Belarusian',
		'bg' => 'Bulgarian',
		'bh' => 'Bihari',
		'bi' => 'Bislama',
		'bm' => 'Bambara',
		'bn' => 'Bengali',
		'bo' => 'Tibetan',
		'br' => 'Breton',
		'bs' => 'Bosnian',
		'ca' => 'Catalan',
		'ce' => 'Chechen',
		'ch' => 'Chamorro',
		'co' => 'Corsican',
		'cr' => 'Cree',
		'cs' => 'Czech',
		'cu' => 'Church Slavic',
		'cv' => 'Chuvash',
		'cy' => 'Welsh',
		'da' => 'Danish',
		'de' => 'German',
		'dv' => 'Dhivehi',
		'dz' => 'Dzongkha',
		'ee' => 'Ewe',
		'el' => 'Greek',
		'en' => 'English',
		'eo' => 'Esperanto',
		'es' => 'Spanish',
		'et' => 'Estonian',
		'eu' => 'Basque',
		'fa' => 'Persian',
		'ff' => 'Fulah',
		'fi' => 'Finnish',
		'fj' => 'Fijian',
		'fo' => 'Faroese',
		'fr' => 'French',
		'fy' => 'Western Frisian',
		'ga' => 'Irish',
		'gd' => 'Gaelic',
		'gl' => 'Galician',
		'gn' => 'Guarani',
		'gu' => 'Gujarati',
		'gv' => 'Manx',
		'ha' => 'Hausa',
		'he' => 'Hebrew',
		'hi' => 'Hindi',
		'ho' => 'Hiri Motu',
		'hr' => 'Croatian',
		'ht' => 'Haitian',
		'hu' => 'Hungarian',
		'hy' => 'Armenian',
		'hz' => 'Herero',
		'ia' => 'Interlingua',
		'id' => 'Indonesian',
		'ie' => 'Interlingue',
		'ig' => 'Igbo',
		'ii' => 'Sichuan Yi',
		'ik' => 'Inupiak',
		'io' => 'Ido',
		'is' => 'Icelandic',
		'it' => 'Italian',
		'iu' => 'Inuktitut',
		'ja' => 'Japanese',
		'jv' => 'Javanese',
		'ka' => 'Georgian',
		'kg' => 'Kongo',
		'ki' => 'Kikuyu',
		'kj' => 'Kuanyama',
		'kk' => 'Kazakh',
		'kl' => 'Greenlandic',
		'km' => 'Cambodian',
		'kn' => 'Kannada',
		'ko' => 'Korean',
		'kr' => 'Kanuri',
		'ks' => 'Kashmiri',
		'ku' => 'Kurdish',
		'kv' => 'Komi',
		'kw' => 'Cornish',
		'ky' => 'Kirghiz',
		'la' => 'Latin',
		'lb' => 'Luxembourgish',
		'lg' => 'Ganda',
		'li' => 'Limburgan',
		'ln' => 'Lingala',
		'lo' => 'Laothian',
		'lt' => 'Lithuanian',
		'lu' => 'Luba-Katanga',
		'lv' => 'Latvian',
		'mg' => 'Malagasy',
		'mh' => 'Marshallese',
		'mi' => 'Maori',
		'mk' => 'Macedonian',
		'ml' => 'Malayalam',
		'mn' => 'Mongolian',
		'mo' => 'Moldavian',
		'mr' => 'Marathi',
		'ms' => 'Malay',
		'mt' => 'Maltese',
		'my' => 'Burmese',
		'na' => 'Nauru',
		'nb' => 'Norwegian Bokmal',
		'nd' => 'North Ndebele',
		'ne' => 'Nepali',
		'ng' => 'Ndonga',
		'nl' => 'Dutch',
		'nn' => 'Norwegian Nynorsk',
		'no' => 'Norwegian',
		'nr' => 'South Ndebele',
		'nv' => 'Navajo',
		'ny' => 'Nyanja',
		'oc' => 'Occitan',
		'oj' => 'Ojibwa',
		'om' => 'Oromo',
		'or' => 'Oriya',
		'os' => 'Ossetian',
		'pa' => 'Punjabi',
		'pi' => 'Pali',
		'pl' => 'Polish',
		'ps' => 'Pushto',
		'pt' => 'Portuguese',
		'qu' => 'Quechua',
		'rm' => 'Romansh',
		'rn' => 'Rundi',
		'ro' => 'Romanian',
		'ru' => 'Russian',
		'rw' => 'Kinyarwanda',
		'sa' => 'Sanskrit',
		'sc' => 'Sardinian',
		'sd' => 'Sindhi',
		'se' => 'Northern Sami',
		'sg' => 'Sangro',
		'sh' => 'Serbo-Croatian',
		'si' => 'Sinhala',
		'sk' => 'Slovak',
		'sl' => 'Slovenian',
		'sm' => 'Samoan',
		'sn' => 'Shona',
		'so' => 'Somali',
		'sq' => 'Albanian',
		'sr' => 'Serbian',
		'ss' => 'Siswati',
		'st' => 'Sesotho',
		'su' => 'Sudanese',
		'sv' => 'Swedish',
		'sw' => 'Swahili',
		'ta' => 'Tamil',
		'te' => 'Tegulu',
		'tg' => 'Tajik',
		'th' => 'Thai',
		'ti' => 'Tigrinya',
		'tk' => 'Turkmen',
		'tl' => 'Tagalog',
		'tn' => 'Tswana',
		'to' => 'Tonga',
		'tr' => 'Turkish',
		'ts' => 'Tsonga',
		'tt' => 'Tatar',
		'tw' => 'Twi',
		'ty' => 'Tahitian',
		'ug' => 'Uighur',
		'uk' => 'Ukrainian',
		'ur' => 'Urdu',
		'uz' => 'Uzbek',
		've' => 'Venda',
		'vi' => 'Vietnamese',
		'vo' => 'Volapuk',
		'wa' => 'Walloon',
		'wo' => 'Wolof',
		'xh' => 'Xhosa',
		'yi' => 'Yiddish',
		'yo' => 'Yoruba',
		'za' => 'Zhuang',
		'zh' => 'Chinese',
		'zu' => 'Zulu'
	);
	
	public static function setLocale($locale)
	{
		self::$locale = $locale;
		//if( $locale != DEFAULT_LOCALE )
			self::loadArray();
	}
	
	public static function getLocale()
	{
		return self::$locale;
	}
	
	public static function getText($string)
	{
		return isset(self::$array[$string]) ? self::$array[$string] : $string;
	}
	
	public static function loadArray()
	{
		$catalog_file = I18N_PATH.DIRECTORY_SEPARATOR.self::$locale.'-message.php';
		
		// assign returned value of catalog file
		// file return a array (source => traduction)
		if (file_exists($catalog_file))
		{
			$array = include $catalog_file;
			self::add($array);
		}
	}
	
	public static function add($array)
	{
		if (!empty($array) && is_array($array))
			self::$array = array_merge(self::$array, $array);
	}
	
	public static function getLanguages()
    {        
        static $languages = array('en' => 'English');
		
		if( count($languages) == 1 )
		{
	        if( $handle = opendir(APP_PATH.'/i18n') )
	        {
	            while( false !== ($file = readdir($handle)) )
	            {
	                if (strpos($file, '.') !== 0)
	                {
	                    $code = substr($file, 0, 2);
	                    $languages[$code] = isset(self::$locale_names[$code]) ? self::$locale_names[$code] : __('unknown');
	                }
	            }
				
	            closedir($handle);
	        }
			
			asort($languages);
		}
		
        return $languages;
    }

} // end I18n class