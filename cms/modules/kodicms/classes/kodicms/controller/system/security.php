<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		ButscHSter
 */
class KodiCMS_Controller_System_Security extends Controller_System_Controller 
{
	/**
	 *
	 * @var bool 
	 */
	public $auth_required = FALSE;
	
	/**
	 *
	 * @var array 
	 */
	public $allowed_actions = array();


	public function before()
	{
		parent::before();

		if (
			$this->auth_required === TRUE
		AND 
			! in_array($this->request->action(), $this->allowed_actions)
		AND 
			! ACL::check( $this->request )
		)
		{
			if ( AuthUser::isLoggedIn() OR $this->request->is_ajax() )
			{
				// Forbidden
				throw HTTP_Exception::factory(403, 'You don`t have permissions to acces this page');
			}
			else
			{
				// Unauthorized / Login Requied
				throw HTTP_Exception::factory(401);
			}
		}
	}
}