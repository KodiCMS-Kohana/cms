<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Installer
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Install extends Controller_System_Api {
	
	public function get_check_connect()
	{
		$post = $this->param('install', array(), TRUE);

		$this->_installer = new Installer;
		
		$validation = Validation::factory($post)
			->label('db_name', __('Database name'))
			->rule('db_name', 'not_empty');
		
		$post['directory'] = TRUE;

		switch ($post['db_driver'])
		{
			case 'pdo::sqlite':
				$validation
					->rule('directory', array($this, 'is_writable'));
				break;
			default:
				$validation
					->label('db_server', __('Database server'))
					->label('db_user', __('Database user'))
					->label('db_password', __('Database password'))
					->rule('db_server', 'not_empty')
					->rule('db_user', 'not_empty');
				break;
		}

		if (!$validation->check())
		{
			throw new Validation_Exception($validation);
		}
		
		$this->status = (bool) $this->_installer->connect_to_db($post);
	}
	
	public function is_writable()
	{
		$path = CMSPATH . 'tedt';
		return (is_dir($path) AND is_writable($path));
	}

	public function execute()
	{		
		if ($this->request->action() == 'index' OR $this->request->action() == '')
		{
			$action = 'rest_' . $this->request->method();
		}
		else
		{
			// Determine the action to use
			$action = $this->request->method() . '_' . $this->request->action();
		}

		$action = strtolower($action);

		try 
		{
			// Execute the "before action" method
			$this->before();

			// If the action doesn't exist, it's a 404
			if (!method_exists($this, $action))
			{
				throw HTTP_API_Exception::factory(API::ERROR_PAGE_NOT_FOUND,
					'The requested method ":method" was not found on this server.',
					array(':method' => $action)
				)->request($this->request);
			}

			// Execute the action itself
			$this->{$action}();
		}
		catch (HTTP_API_Exception $e)
		{
			$this->json = $e->get_response();
		}
		catch (API_Validation_Exception $e)
		{
			$this->json = $e->get_response();
		}
		catch (ORM_Validation_Exception $e)
		{
			$this->json = array(
				'code'  => API::ERROR_VALIDATION,
				'message' => array(rawurlencode($e->getMessage())),
				'response' => NULL,
				'errors' => $e->errors('validation')
			);
		}
		catch (Validation_Exception $e)
		{
			$this->json = array(
				'code'  =>  API::ERROR_VALIDATION,
				'message' => array(rawurlencode($e->getMessage())),
				'response' => NULL,
				'errors' => $e->errors('validation')
			);
		}
		catch (Exception $e)
		{
			$this->json['code'] = $e->getCode();
			$this->json['line'] = $e->getLine();
			$this->json['file'] = $e->getFile();
			$this->json['message'][] = $e->getMessage();
			$this->json['response'] = NULL;
		}
		
		// Execute the "after action" method
		$this->after();

		// Return the response
		return $this->response;
	}
}