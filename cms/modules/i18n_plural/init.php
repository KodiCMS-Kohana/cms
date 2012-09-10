<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana translation/internationalization function with context support.
 * The PHP function [strtr](http://php.net/strtr) is used for replacing parameters.
 *
 *    ___(':count user is online', 1000, array(':count' => 1000));
 *    // 1000 users are online
 *
 * @uses I18n_Plural::get()
 * @uses I18n_Form::get()
 * @param string $string to translate
 * @param mixed $context string form or numeric count
 * @param array $values param values to insert
 * @param string $lang target language
 * @return string
 */
function ___($string, $context = 0, $values = NULL, $lang = NULL)
{
	if (is_array($context) AND ! is_array($values))
	{
		// Assume no form is specified and the 2nd argument are parameters
		$lang = $values;
		$values = $context;
		$context = 0;
	}
	if (is_numeric($context))
	{
		// Get plural form
		$string = I18n_Plural::get($string, $context, $lang);
	}
	else
	{
		// Get custom form
		$string = I18n_Form::get($string, $context, $lang);
	}
	return empty($values) ? $string : strtr($string, $values);
}

// Force load I18n class, if not done already
I18n::lang();