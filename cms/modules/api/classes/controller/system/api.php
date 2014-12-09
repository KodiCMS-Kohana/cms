<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/API
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_System_API extends Controller_System_Ajax {
	
	/**
	 *
	 * @var Model_API_Key 
	 */
	protected $_model = NULL;

	/**
	 * Массив возвращаемых значений, будет преобразован в формат JSON
	 * @var array 
	 */
	public $json = array();
	
	/**
	 *
	 * @var array 
	 */
	public $fields = array();
	
	/**
	 * Передаваемые параметры.
	 * Значения берутся из массивов $_GET, $_POST, $_FILES, Request::params, JSON
	 * 
	 * @var array 
	 */
	protected $_params = array();
	
	/**
	 * Публичные методы, к которым можно получить доступ без ключа или авторизации
	 * 
	 * @var array array('post_action', 'get_action', '...')
	 */
	public $public_actions = array();

	/**
	 * Осуществялть проверку токена для входящих данных
	 * При активации необходимо передавать параметр token
	 * 
	 * @var bool
	 */
	protected $_check_token = FALSE;

	public function before()
	{
		parent::before();
		
		$this->json['code'] = API::NO_ERROR;
		
		$this->fields = $this->param('fields', array());
		
		
		if (strpos($this->request->headers('content-type'), 'application/json') !== FALSE)
		{
			$data = json_decode($this->request->body(), TRUE);
			
			if ( ! is_array( $data ))
			{
				parse_str($this->request->body(), $data);
			}
		
			$this->request->post($data);
		}
	}
	
	/**
	 * Получение значения передаваемого параметра
	 * 
	 * Если параметр указан как обязательный, то при его отсутсвии на запрос 
	 * вернется ошибка
	 * 
	 * @param string $key Ключ
	 * @param mixed $default Значение по умолчанию, если параметр отсутсвует
	 * @param bool $is_required Параметр обязателен для передачи
	 * @return string
	 * @throws HTTP_API_Exception
	 */
	public function param($key, $default = NULL, $is_required = FALSE)
	{
		$param = Arr::get($this->params(), $key, $default);
		
		if($is_required === TRUE AND empty($param))
		{
			throw HTTP_API_Exception::factory(API::ERROR_MISSING_PAPAM, 'Missing param :key', array(
				':key' => $key ));
		}
		
		return $param;
	}
	
	/**
	 * Получение списка всех параметров
	 * 
	 * @param array $new_params
	 * @return array
	 */
	public function params(array $new_params = NULL)
	{
		$this->_params = Arr::merge($this->request->query(), $this->request->post(), $this->request->param(), $_FILES);
		
		if(is_array($new_params))
		{
			$this->_params = Arr::merge($this->_params, $new_params);
		}
		
		return $this->_params;
	}

	/**
	 * 
	 * @return Response
	 * @throws HTTP_API_Exception
	 */
	public function execute()
	{
		$this->_model = ORM::factory('Api_Key');
		
		if($this->request->action() == 'index' OR $this->request->action() == '')
		{
			$action = 'rest_'.$this->request->method();
		}
		else
		{
			// Determine the action to use
			$action = $this->request->method() . '_' . $this->request->action();
		}

		$action = strtolower($action);

		$is_logged_in = Auth::is_logged_in();

		try 
		{
			/**
			 * Если выключено API, запретить доступ не авторизованным пользователям к нему
			 */
			if ((Config::get('api', 'mode') == 'no' AND ( !$is_logged_in AND $this->is_backend())))
			{
				throw new HTTP_Exception_403('Forbiden');
			}
			
			/**
			 * Если невалидный ключ и пользователь не авторизован 
			 * или экшен не публичный то запретить доступ к API
			 */
			if (!$is_logged_in AND ! in_array($action, $this->public_actions))
			{
				if (!$this->_model->is_valid($this->param('api_key')))
				{
					throw new HTTP_Exception_403('Api key not valid');
				}
			}

			// Execute the "before action" method
			$this->before();

			/**
			 * Проверка токена на валидность, если этого требует экшен или контроллер
			 */
			if ($this->_check_token !== FALSE)
			{
				$this->_check_token();
			}

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
				'message' => rawurlencode($e->getMessage()),
				'response' => NULL,
				'errors' => $e->errors('validation')
			);
		}
		catch (Validation_Exception $e)
		{
			$this->json = array(
				'code'  =>  API::ERROR_VALIDATION,
				'message' => rawurlencode($e->getMessage()),
				'response' => NULL,
				'errors' => $e->errors('validation')
			);
		}
		catch (Exception $e)
		{
			$this->json['code'] = $e->getCode();
			$this->json['line'] = $e->getLine();
			$this->json['file'] = $e->getFile();
			$this->json['message'] = $e->getMessage();
			$this->json['response'] = NULL;
		}
		
		// Execute the "after action" method
		$this->after();

		// Return the response
		return $this->response;
	}

	public function after()
	{
		if ($this->param('debug') !== NULL)
		{
			$this->response->body(debug::vars($this->json));
			return;
		}

		if (is_array($this->json))
		{
			$this->request->headers('Content-type', 'application/json');

			if (!isset($this->json['response']))
			{
				$this->json['response'] = NULL;
			}

			$this->json = json_encode($this->json);
		}

		$this->response->body($this->json);
	}
	
	/**
	 * 
	 * @param string $uri
	 */
	public function json_redirect($uri)
	{
		$this->json['redirect'] = URL::backend($uri);
	}
	
	/**
	 * 
	 * @param string $uri
	 */
	public function message( $message, array $values = NULL )
	{
		$this->json['message'] = __($message, $values);
	}

	/**
	 * 
	 * @param mixed $data
	 */
	public function response($data)
	{
		$this->json['type'] = $this->request->method();
		$this->json['response'] = $data;
	}
	
	/**
	 * Проверка токена на валидность
	 * 
	 * @throws HTTP_API_Exception
	 */
	protected function _check_token()
	{
		$token = $this->param('token', NULL, TRUE);

		if (!Security::check($token))
		{
			Kohana::$log->add(Log::NOTICE, 'Error security token')->write();
			throw HTTP_API_Exception::factory(API::ERROR_TOKEN, 'Error security token');
		}
	}
	
	/**
	 * Проверка из какой части системы пользователь получает доступ к API
	 * 
	 * @return boolean
	 */
	public function is_backend()
	{
		return  $this->request->param('backend') === ADMIN_DIR_NAME;
	}
	
	/***************************************
	 * Magic methods
	 ***************************************/
	public function __set($name, $value)
	{
		$this->json[$name] = $value;
	}
	
	public function __get($name)
	{
		return $this->json[$name];
	}
	
	public function __isset($name)
	{
		return isset($this->json[$name]);
	}
	
	public function __unset($name)
	{
		unset($this->json[$name]);
	}
}
