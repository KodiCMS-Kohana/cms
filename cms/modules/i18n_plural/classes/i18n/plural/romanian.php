<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plural rules for the following locales and languages:
 * 
 * Locales: ro mo
 *
 * Languages:
 *  Moldavian (mo)
 *  Romanian (ro)
 *
 * Rules:
 * 	one → n is 1;
 * 	few → n is 0 OR n is not 1 AND n mod 100 in 1..19;
 * 	other → everything else
 * 
 * Reference CLDR Version 1.9 beta (2010-11-16 21:48:45 GMT)
 * @see http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html
 * @see http://unicode.org/repos/cldr/trunk/common/supplemental/plurals.xml
 * @see plurals.xml (local copy)
 *
 * @package		I18n_Plural
 * @author		Korney Czukowski
 * @copyright	(c) 2010 Korney Czukowski
 * @license		http://kohanaphp.com/license
 */
class I18n_Plural_Romanian extends I18n_Plural_Rules
{
	public function get_category($count)
	{
		if ($count == 1)
		{
			return 'one';
		}
		elseif (is_int($count) AND ($count == 0 OR (($i = $count % 100) >= 1 AND $i <= 19)))
		{
			return 'few';
		}
		else
		{
			return 'other';
		}
	}
}