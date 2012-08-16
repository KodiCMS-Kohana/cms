<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * File-based configuration reader. Multiple configuration directories can be
 * used by attaching multiple instances of this class to [Config].
 *
 * @package    Core
 * @category   Configuration
 * @author     Core Team
 * @copyright  (c) 2009-2010 Core Team
 * @license    http://Coreframework.org/license
 */
class Config_File_Reader implements Config_Reader {

	/**
	 * The directory where config files are located
	 * @var string
	 */
	protected $_directory = '';

	/**
	 * Creates a new file reader using the given directory as a config source
	 *
	 * @param string Configuration directory to search
	 */
	public function __construct($directory = 'config')
	{
		// Set the configuration directory name
		$this->_directory = trim($directory, '/');
	}

	/**
	 * Load and merge all of the configuration files in this group.
	 *
	 *     $config->load($name);
	 *
	 * @param   string  configuration group name
	 * @return  $this   current object
	 * @uses    Core::load
	 */
	public function load($group)
	{
		$config = array();

		if ($files = Core::find_file($this->_directory, $group, NULL, TRUE))
		{
			foreach ($files as $file)
			{
				// Merge each file to the configuration array
				$config = Arr::merge($config, Core::load($file));
			}
		}

		return $config;
	}

} // End Config
