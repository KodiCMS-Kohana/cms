<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Navigation
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Navigation_Page extends Model_Navigation_Abstract {

	protected $_section = NULL;
	
	/**
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @return \Model_Navigation_Page
	 */
	public function __set($name, $value)
	{
		parent::__set($name, $value);

		if ($this->_section !== NULL)
		{
			$this->_section->update();
		}

		return $this;
	}

	/**
	 * 
	 * @param boolean $status
	 * @return \Model_Navigation_Page
	 */
	public function set_active($status = TRUE)
	{
		parent::set_active($status);
		$this->_section->set_active($status);

		return $this;
	}

}