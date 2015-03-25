<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Exception
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Validation_Exception extends Kohana_Exception 
{
	/**
	* Array of validation objects
	* @var array
	*/
	protected $_objects = array();
	
	/**
	 * Constructs a new exception for the specified model
	 *
	 * @param  Validation $object      The Validation object of the model
	 * @param  string     $message     The error message
	 * @param  array      $values      The array of values for the error message
	 * @param  integer    $code        The error code for the exception
	 * @return void
	 */
	public function __construct(Validation $object, $message = 'Failed to validate array', array $values = NULL, $code = 0)
	{
		$this->_objects['_object'] = $object;
		parent::__construct($message, $values, $code);
	}
	
	/**
	 * Adds a Validation object to this exception
	 *
	 *     // The following will add a validation object for a profile model
	 *     // inside the exception for a user model.
	 *     $e->add_object('profile', $validation);
	 *     // The errors array will now look something like this
	 *     // array
	 *     // (
	 *     //   'username' => 'This field is required',
	 *     //   'profile'  => array
	 *     //   (
	 *     //     'first_name' => 'This field is required',
	 *     //   ),
	 *     // );
	 *
	 * @param  string     $alias    The relationship alias from the model
	 * @param  Validation $object   The Validation object to merge
	 * @param  mixed      $has_many The array key to use if this exception can be merged multiple times
	 * @return ORM_Validation_Exception
	 */
	public function add_object( Validation $object )
	{
		$this->_objects[] = $object;

		return $this;
	}
	
	/**
	 * Returns a merged array of the errors from all the Validation objects in this exception
	 *
	 *     // Will load Model_User errors from messages/orm-validation/user.php
	 *     $e->errors('orm-validation');
	 *
	 * @param   string  $directory Directory to load error messages from
	 * @param   mixed   $translate Translate the message
	 * @return  array
	 * @see generate_errors()
	 */
	public function errors($directory = NULL, $translate = TRUE)
	{
		return $this->generate_errors($this->_objects, $directory, $translate);
	}
	
	/**
	 * Recursive method to fetch all the errors in this exception
	 *
	 * @param  array  $array     Array of Validation objects to get errors from
	 * @param  string $directory Directory to load error messages from
	 * @param  mixed  $translate Translate the message
	 * @return array
	 */
	protected function generate_errors(array $array, $directory, $translate)
	{
		$errors = array();

		foreach ($array as $key => $object)
		{
			if ($object instanceof Validation)
			{
				if ($directory === NULL)
				{
					// Return the raw errors
					$file = NULL;
				}
				else
				{
					$file = trim($directory);
				}

				// Merge in this array of errors
				$errors += $object->errors($file, $translate);
			}
		}

		return $errors;
	}
	
	/**
	 * Returns the protected _objects property from this exception
	 *
	 * @return array
	 */
	public function objects()
	{
		return $this->_objects;
	}

}
