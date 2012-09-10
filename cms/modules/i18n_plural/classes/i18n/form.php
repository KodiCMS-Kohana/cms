<?php defined('SYSPATH') or die('No direct script access.');

class I18n_Form
{
	/**
	 * Returns specified form of a string translation. If no translation exists, the original string will be
	 * returned. No parameters are replaced.
	 *
	 *     $hello = I18n_Form::get('I\'ve met :name, he is my friend now.', 'f');
	 *     // 'I\'ve met :name, she is my friend now.'
	 *
	 * @param string $string
	 * @param string $form, if NULL, looking for 'other' form, else the very first form
	 * @param string $lang
	 * @return string
	 */
	public static function get($string, $form = NULL, $lang = NULL)
	{
		$translation = I18n::get($string, $lang);
		if (is_array($translation))
		{
			if (array_key_exists($form, $translation))
			{
				return $translation[$form];
			}
			elseif (array_key_exists('other', $translation))
			{
				return $translation['other'];
			}
			return reset($translation);
		}
		return $translation;
	}
}