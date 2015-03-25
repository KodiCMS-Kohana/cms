<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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
	
	/**
	 *
	 * @var array 
	 */
	public $public_actions = array();
	
	public function before()
	{
		parent::before();

		if (
			$this->auth_required === TRUE
		AND
			! Auth::is_logged_in()
		AND
			! in_array($this->request->action(), $this->public_actions)
		)
		{
			$this->_deny_access();
		}
		
		if (
			$this->auth_required === TRUE
		AND
			! in_array($this->request->action(), $this->allowed_actions)
		AND 
			! ACL::check($this->request)
		)
		{
			$this->_deny_access();
		}
	}
	
	/**
	 * 
	 * @param string $message
	 * @throws HTTP_Exception
	 */
	protected function _deny_access($message = NULL)
	{
		if (Auth::is_logged_in() OR $this->request->is_ajax())
		{
			if ($message === NULL)
			{
				$message = 'You don`t have permissions to acces this page';
			}

			// Forbidden
			throw HTTP_Exception::factory(403, $message);
		}
		else
		{
			// Unauthorized / Login Requied
			throw HTTP_Exception::factory(401);
		}
	}
}