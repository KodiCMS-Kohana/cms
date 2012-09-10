<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plural rules for Tachelhit language:
 *
 * Locales: shi
 *
 * Languages:
 * - Tachelhit (shi)
 *
 * Rules:
 * 	one → n within 0..1;
 * 	few → n in 2..10;
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
class I18n_Plural_Tachelhit extends I18n_Plural_Rules
{
	public function get_category($count)
	{
		if ($count >= 0 AND $count <= 1)
		{
			return 'one';
		}
		elseif (is_int($count) AND $count >= 2 AND $count <= 10)
		{
			return 'few';
        }
		else
		{
			return 'other';
        }
	}
}