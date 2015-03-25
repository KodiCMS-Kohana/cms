<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * File Auth driver.
 * [!!] this Auth driver does not support roles nor autologin.
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class KodiCMS_Auth_Fake extends Auth_ORM {

	// User list
	protected $_user;

	/**
	 * Constructor loads the user list into the class.
	 */
	public function __construct($config = array(), $id)
	{
		parent::__construct($config);

		// Load user list
		$this->_user = ORM::factory('User', $id);
	}
	
	public function get_user($default = NULL)
	{
		return $this->_user;
	}
	
	public function force_login($user, $mark_session_as_forced = FALSE)
	{
		return TRUE;
	}

	public function auto_login()
	{
		return TRUE;
	}
	
	protected function complete_login($user)
	{
		return TRUE;
	}

	public function logout($destroy = FALSE, $logout_all = FALSE)
	{
		Auth::stop_run_as();
	}
}
