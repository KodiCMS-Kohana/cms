<?php defined('SYSPATH') or die('No direct script access.');
/**
 * I18n_Validation class
 * Attempts to provide grammatically accurate error translations, where plurals are involved
 * The I18n_Validation::errors() method is a slightly modified original Kohana_Validation::errors()
 * 
 * @package		I18n_Plural
 * @author		Korney Czukowski
 * @copyright	(c) 2010 Korney Czukowski
 * @license		http://kohanaphp.com/license
 */
class I18n_Validation extends Kohana_Validation
{
	/**
	 * Returns the error messages. If no file is specified, the error message
	 * will be the name of the rule that failed. When a file is specified, the
	 * message will be loaded from "field/rule", or if no rule-specific message
	 * exists, "field/default" will be used. If neither is set, the returned
	 * message will be "file/field/rule".
	 *
	 * By default all messages are translated using the default language.
	 * A string can be used as the second parameter to specified the language
	 * that the message was written in.
	 *
	 *     // Get errors from messages/forms/login.php
	 *     $errors = $validate->errors('forms/login');
	 *
	 * @uses    Kohana::message
	 * @param   string  file to load error messages from
	 * @param   mixed   translate the message
	 * @return  array
	 */
	public function errors($file = NULL, $translate = TRUE)
	{
		if ($file === NULL)
		{
			// Return the error list
			return $this->_errors;
		}

		// Create a new message list
		$messages = array();

		foreach ($this->_errors as $field => $set)
		{
			list($error, $params) = $set;

			// Get the label for this field
			$label = $this->_labels[$field];

			if ($translate)
			{
				if (is_string($translate))
				{
					// Translate the label to the specified language
					$label = ___($label, NULL, array(), $translate);
				}
				else
				{
					// Translate the label
					$label = ___($label);
				}
			}

			// Start the translation values list
			$values = array(
				':field' => $label,
				':value' => Arr::get($this, $field),
			);

			if (is_array($values[':value']))
			{
				// All values must be strings
				$values[':value'] = implode(', ', Arr::flatten($values[':value']));
			}

			$context = NULL;
			if ($params)
			{
				foreach ($params as $key => $value)
				{
					if (is_array($value))
					{
						// All values must be strings
						$value = implode(', ', Arr::flatten($value));
					}

					// Check if a label for this parameter exists
					if (isset($this->_labels[$value]))
					{
						// Use the label as the value, eg: related field name for "matches"
						$value = $this->_labels[$value];

						if ($translate)
						{
							if (is_string($translate))
							{
								// Translate the value using the specified language
								$value = ___($value, NULL, array(), $translate);
							}
							else
							{
								// Translate the value
								$value = ___($value);
							}
						}
					}

					// Add each parameter as a numbered value, starting from 1
					$values[':param'.($key + 1)] = $value;

					// Starting from 2nd parameter, detect context (1st is validation context)
					if ($context === NULL AND $key > 0 AND is_numeric($value))
					{
						$context = $value;
					}
				}
			}

			$path = "{$file}.{$field}.{$error}";
			if ( (bool) ($message = Kohana::message($file, "{$field}.{$error}")))
			{
				// Found a message for this field and error
			}
			elseif ( (bool) ($message = Kohana::message($file, "{$field}.default")))
			{
				// Found a default message for this field
			}
			elseif ( (bool) ($message = Kohana::message($file, $error)))
			{
				// Found a default message for this error
			}
			elseif ($translate)
			{
				// No message exists
				$message = NULL;
			}
			else
			{
				$message = $path;
			}

			if ($translate)
			{
				if ($message !== NULL)
				{
					$translated = ___($message, $context, $values);
				}
				elseif (($translated = ___($path, $context, $values)) != $path)
				{
					// Found path translation
				}
				elseif (($translated = ___('valid.'.$error, $context, $values)) != 'valid.'.$error)
				{
					// Found a default translation for this error
				}
				elseif ( (bool) ($message = Kohana::message('validate', $error)))
				{
					// Found a default message for this error
					$translated = ___($message, $context, $values);
				}
				else
				{
					$translated = $path;
				}
				$message = $translated;
			}
			else
			{
				// Do not translate, just replace the values
				$message = strtr($message, $values);
			}

			// Set the message for this field
			$messages[$field] = $message;
		}

		return $messages;
	}
}