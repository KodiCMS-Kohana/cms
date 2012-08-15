<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Flexo CMS.
 *
 * Flexo CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flexo CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flexo CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Flexo CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package Flexo
 * @subpackage controllers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

/**
 * Allows a user to access login/logout related functionality.
 * 
 * It also has functionality to email a new password to the user if that user
 * cannot remember his or her password.
 *
 * @package flexo
 * @subpackage controllers
 * 
 * @version 0.1
 * @since 0.1
 */
class LoginController extends Controller
{
	/**
	* Sets up the LoginController.
	*/
    function __construct()
    {
        AuthUser::load();
		
		$this->setLayout('login');
    }

	
	/**
	* Checks if a user is already logged in, otherwise it redirects the user
	* to the login screen.
	*/
    function index()
    {
        // already log in ?
        if( AuthUser::isLoggedIn() )
            $this->_redirect(Flash::get('redirect'));
        
        // show it!
        $this->display('login/login', array(
            'username' => Flash::get('username'),
            'redirect' => Flash::get('redirect')
        ));
    }

	
	/**
	* Allows a user to login.
	*/
    function login()
    {
        // already log in ?
        if( AuthUser::isLoggedIn() )
			$this->_redirect( Flash::get('redirect') );
        
        if( get_request_method() == 'POST' )
		{
            $data = isset($_POST['login']) ? $_POST['login'] : array('username' => '', 'password' => '');
            Flash::set('username', $data['username']);
			
			Observer::notify('admin_login_before', array($data));
			
            if( AuthUser::login($data['username'], $data['password'], isset($data['remember'])) )
            {
                Observer::notify('admin_login_success', array($data['username']));
                
                $this->_checkVersion();
                
				$redirect = (empty($data['redirect']) ? get_url(Setting::get('default_tab')): $data['redirect']);
				
				// redirect to defaut controller and action
                $this->_redirect( $redirect );
            }
            else
			{
                Flash::set('error', __('Login failed. Please check your login data and try again.'));
                Observer::notify('admin_login_failed', array($data['username']));
				
				redirect(get_url('login'));
            }
        }
        
        // not find or password is wrong
        $this->_redirect( $data['redirect'] );
    }

	
	/**
	* Allows a user to logout.
	*/
    function logout()
    {
        $username = AuthUser::getUserName();
        AuthUser::logout();
        Observer::notify('admin_after_logout', array($username));
        redirect(get_url());
    }

	
	/**
	* Allows a user to request a new password be mailed to him/her.
	*
	* @return <type> ???
	*/
    function forgot()
    {
        if (get_request_method() == 'POST')
            return $this->_sendPasswordTo($_POST['forgot']['email']);
        
        $this->display('login/forgot', array('email' => Flash::get('email')));
    }

    
	/**
	* This method is used to send a newly generated password to a user.
	* 
	* @param string $email The user's email adress.
	*/
    private function _sendPasswordTo($email)
    {	
        $user = User::findBy('email', $email);
		
		Observer::notify('admin_login_forgot_before', array($user));
		
        if( $user )
        {
			Flash::set('email', $email);
		
            use_helper('Email');
            
            $new_pass = '12'.dechex(rand(100000000, 4294967295)).'K';
            $user->password = sha1($new_pass);
            $user->save();
            
			$message = new View('login/email', array(
				'username' => $user->username,
				'password' => $new_pass
			));
			
			$site_host = dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
			
            $email = new Email();
            $email->from('no-reply@' . $site_host, Setting::get('admin_title'));
            $email->to($user->email);
            $email->subject(__('Your new password from :site_name', array(':site_name' => Setting::get('admin_title'))));
            $email->message($message);
            $email->send();
            
            Flash::set('success', __('An email has been send with your new password!'));
            redirect(get_url('login'));
        }
        else
        {
            Flash::set('error', __('No user found!'));
            redirect(get_url('login/forgot'));
        }
    }

	private function _redirect( $redirect )
	{
		if( $redirect != null && $redirect != '' )
		{
			$redirect_url = parse_url($redirect);
			$local_url = parse_url(get_url());
			
			if( $redirect_url['host'] == $local_url['host'] )
				redirect($redirect);
			else
				redirect(get_url());
		}
		else
			redirect(get_url());
	}
	
	
	/**
	* Checks what the latest Frog version is that is available at madebyfrog.com
	*/
    private function _checkVersion()
    {
        if( !defined('CHECK_UPDATES') || !CHECK_UPDATES )
            return;
		
        if( !defined('CHECK_TIMEOUT')) define('CHECK_TIMEOUT', 5);
        $ctx = stream_context_create(array('http' => array('timeout' => CHECK_TIMEOUT)));
        
        $v = file_get_contents('http://www.madebyfrog.com/version/', 0, $ctx);
        if( $v > FROG_VERSION )
        {
            Flash::set('notice', __('<b>Information!</b> New Frog version available (v. <b>:version</b>)! Visit <a href="http://www.madebyfrog.com/">http://www.madebyfrog.com/</a> to upgrade your version!',
                       array(':version' => $v )));
        }
    }
    
} // end LoginController class
