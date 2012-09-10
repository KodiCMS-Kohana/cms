<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plural rules for Maltese language:
 * 
 * Locales: mt
 *
 * Languages:
 * - Maltese (mt)
 *
 * Rules:
 * 	one → n is 1;
 * 	few → n is 0 or n mod 100 in 2..10;
 * 	many → n mod 100 in 11..19;
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
class I18n_Plural_Maltese extends I18n_Plural_Rules
{
	public function get_category($count)
	{
		if ($count == 1)
		{
			return 'one';
		}
		elseif ($count == 0 OR is_int($count) AND ($i = $count % 100) >= 2 AND $i <= 10)
		{
			return 'few';
		}
		elseif (is_int($count) AND ($i = $count % 100) >= 11 AND $i <= 19)
		{
			return 'many';
		}
		else
		{
			return 'other';
		}
	}
}