<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Base I18n_Plural_Rules class
 *
 * @package		I18n_Plural
 * @author		Korney Czukowski
 * @copyright	(c) 2010 Korney Czukowski
 * @license		http://kohanaphp.com/license
 */
abstract class I18n_Plural_Rules
{
	/**
	 * Returns category key
	 * @param int $count
	 * @return string
	 */
	abstract public function get_category($count);
}