<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plural rules for Langi language:
 *
 * Locales: lag
 *
 * Languages:
 * - Langi (lag)
 *
 * Rules:
 * 	zero → n is 0;
 * 	one → n within 0..2 and n is not 0 and n is not 2;
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
class I18n_Plural_Langi extends I18n_Plural_Rules
{
	public function get_category($count)
	{
		if ($count == 0)
		{
			return 'zero';
		}
		elseif ($count > 0 AND $count < 2)
		{
			return 'one';
        }
		else
		{
			return 'other';
        }
	}
}