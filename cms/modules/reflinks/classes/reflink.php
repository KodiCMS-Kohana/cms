<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Reflink
 * @author		ButscHSter
 */
abstract class Reflink {
	
	public static function factory( Model_User_Reflink $reflink )
	{
		$class_name = 'Reflink_' . ucfirst($reflink->type);
		
		if( ! class_exists($class_name) )
		{
			throw new Reflink_Exception('Class :class not exists', array(
				':class' =>$class_name));
		}
		
		$object = new $class_name;
		$object->model = $reflink;

		return $object;
	}
	
	/**
	 *
	 * @var Model_User_Reflink 
	 */
	protected $model = NULL;
	
	abstract public function confirm();
}