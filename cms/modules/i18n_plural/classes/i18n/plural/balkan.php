<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plural rules for the following locales and languages
 * 
 * Locales: hr ru sr uk be bs sh
 *
 * Languages:
 * - Belarusian (br)
 * - Bosnian (bs)
 * - Croatian (hr)
 * - Russian (ru)
 * - Serbo-Croatian (sh)
 * - Serbian (sr)
 * - Ukrainian (uk)
 *
 * Rules:
 * 	one → n mod 10 is 1 and n mod 100 is not 11;
 * 	few → n mod 10 in 2..4 and n mod 100 not in 12..14;
 * 	many → n mod 10 is 0 or n mod 10 in 5..9 or n mod 100 in 11..14;
 * 	other → everything else (fractions)
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
class I18n_Plural_Balkan extends I18n_Plural_Rules
{
	public function get_category($count)
	{
		if (is_int($count) AND $count % 10 == 1 AND $count % 100 != 11)
		{
			return 'one';
		}
		else if (is_int($count) AND ($i = $count % 10) >= 2 AND $i <= 4 AND ! (($i = $count % 100) >= 12 AND $i <= 14)) 
		{
			return 'few';
		}
		else if (is_int($count) AND (($i = $count % 10) == 0 OR ($i >= 5 AND $i <= 9) OR (($i = $count % 100) >= 11 AND $i <= 14)))
		{
			return 'many';
		}
		else
		{
			return 'other';
		}
	}
}