<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Filter implements ArrayAccess {
	
	/**
	 * Creates a new Filter instance.
	 *
	 * @param   array   $array  array to use for filter
	 * @return  Filter
	 */
	public static function factory(array $array = NULL, array $rules = NULL)
	{
		return new Filter($array, $rules);
	}

	// Array to filter
	protected $_data = array();
	
	// Field rules
	protected $_rules = array();
	
	/**
	 * Sets the unique "any field" key and creates an ArrayObject from the
	 * passed array.
	 *
	 * @param   array   $array  array to filter
	 * @param array $rules rules [field => array(..., rules, ...)]
	 * @return  void
	 */
	public function __construct(array $array = NULL, array $rules = NULL)
	{
		$this->_data = $array;

		if (!empty($rules))
		{
			foreach ($rules as $field => $data)
			{
				$this->rules($field, $data);
			}
		}
	}

	/**
	 * Returns the array of data to be filter.
	 *
	 * @return  array
	 */
	public function data()
	{
		return $this->_data;
	}
	
	/**
	 * @param   string      $field  field name
	 * @param   callback    $rule   valid PHP callback or closure
	 * @param   mixed       $default default value
	 * @return  $this
	 */
	public function rule($field, $rule, $default = NULL)
	{
		if (!is_bool($rule) AND ! is_null($rule))
		{
			// Store the rule and params for this rule
			$this->_rules[$field]['rules'][] = $rule;
		}

		$this->_rules[$field]['default'] = $default;

		return $this;
	}

	/**
	 * Add rules using an array.
	 *
	 * @param   string|array  $field  field name
	 * @param   array   $rules  list of callbacks
	 * @return  $this
	 */
	public function rules($field, array $rules = NULL)
	{
		if (!empty($rules))
		{
			foreach ($rules as $rule)
			{
				$this->rule($field, $rule[0], Arr::get($rule, 1));
			}
		}

		return $this;
	}

	/**
	 * Filters a values
	 */
	public function run()
	{
		$rules = $this->_rules;

		// Get the filters for this column
		$wildcards = empty($rules[TRUE]) ? array() : $rules[TRUE];
		
		foreach ($rules as $field => $data)
		{
			$data['rules'] = empty($data['rules']) ? $wildcards : array_merge($wildcards, $data['rules']);

			if ($this->offsetExists($field))
			{
				$value = $this->offsetGet($field);
			}
			elseif (!$this->offsetExists($field) AND ! empty($data['default']))
			{
				Arr::set_path($this->_data, $field, $data['default']);
				continue;
			}

			$value = $this->field($field, $value, $data['rules']);

			Arr::set_path($this->_data, $field, $value);
		}

		return $this;
	}

	/**
	 * 
	 * @param string $field
	 * @param mixed $value
	 * @param array $rules
	 * @return mixed
	 */
	public function field($field, $value, array $rules = NULL)
	{
		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Filter field', $field);
		}

		if (empty($rules))
		{
			$rules = Arr::path($this->_rules, $field . '.rules', array());
		}

		// Bind the field name and model so they can be used in the filter method
		$_bound = array(
			':field' => $field,
			':filter' => $this,
		);

		foreach ($rules as $filter)
		{
			// Value needs to be bound inside the loop so we are always using the
			// version that was modified by the filters that already ran
			$_bound[':value'] = $value;
			$params = array(':value');

			foreach ($params as $key => $param)
			{
				if (is_string($param) AND array_key_exists($param, $_bound))
				{
					// Replace with bound value
					$params[$key] = $_bound[$param];
				}
			}

			$value = Callback::invoke($filter, $params);
		}

		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}

		return $value;
	}

	/**
	 * Throws an exception because Filter is read-only.
	 * Implements ArrayAccess method.
	 *
	 * @throws  Kohana_Exception
	 * @param   string   $offset    key to set
	 * @param   mixed    $value     value to set
	 * @return  void
	 */
	public function offsetSet($offset, $value)
	{
		Arr::set_path($this->_data, $offset, $value);
	}

	/**
	 * Checks if key is set in array data.
	 * Implements ArrayAccess method.
	 *
	 * @param   string  $offset key to check
	 * @return  bool    whether the key is set
	 */
	public function offsetExists($offset)
	{
		return Arr::path($this->_data, $offset, '!isset') != '!isset';
	}

	/**
	 * Throws an exception because Filter is read-only.
	 * Implements ArrayAccess method.
	 *
	 * @throws  Kohana_Exception
	 * @param   string  $offset key to unset
	 * @return  void
	 */
	public function offsetUnset($offset)
	{
		throw new Kohana_Exception('Filter objects are read-only.');
	}

	/**
	 * Gets a value from the array data.
	 * Implements ArrayAccess method.
	 *
	 * @param   string  $offset key to return
	 * @return  mixed   value from array
	 */
	public function offsetGet($offset)
	{
		return Arr::path($this->_data, $offset);
	}
}