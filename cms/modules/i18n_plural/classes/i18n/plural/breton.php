<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Plural rules for Breton language:
 *
 * Locales: br
 *
 * Languages:
 * - Breton (br)
 *
 * Rules:
 *  zero → n is 0;
 *  one → n is 1;
 *  two → n is 2;
 *  few → n is 3;
 *  many → n is 6;
 *  other → everything else
 *
 * Note: for now, the rules are the same as with Welsh language, but according to the ticket
 * http://unicode.org/cldr/trac/ticket/2886 it's probably going to change
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
class I18n_Plural_Breton extends I18n_Plural_Rules
{
	public function get_category($count)
	{
		if ($count == 1)
		{
			return 'one';
		}
		elseif ($count == 2)
		{
			return 'two';
		}
		elseif ($count == 3)
		{
			return 'few';
		}
		elseif ($count == 6)
		{
			return 'many';
		}
		else
		{
			return 'other';
		}
	}
}