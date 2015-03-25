<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Reflink
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class Reflink {
	
	public static function factory(Model_User_Reflink $reflink)
	{
		$class_name = 'Reflink_' . ucfirst($reflink->type);
		
		if (!class_exists($class_name))
		{
			throw new Reflink_Exception('Class :class not exists', array(
				':class' => $class_name));
		}
		 
		return new $class_name($reflink);
	}
	
	/**
	 *
	 * @var Model_User_Reflink 
	 */
	protected $_model = NULL;
	
	public function __construct(Model_User_Reflink $reflink)
	{
		$this->_model = $reflink;
	}

	abstract public function confirm();
}