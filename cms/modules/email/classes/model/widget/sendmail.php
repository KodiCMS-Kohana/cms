<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Other
 * @author		ButscHSter
 */
class Model_Widget_SendMail extends Model_Widget_Decorator {

	const CTX = 0;
	const GET = 1;
	const POST = 2;
	const COOKIE = 3;
	const SESSION = 4;

	const TXT = 10;
	const HTML = 20;

	protected $_errors = array();
	protected $_messages = array();
	
	public $use_template = FALSE;

	public $fields = array();
	
	protected $_data = array(
		'allowed_tags' => '<b><i><u><p>'
	);

	public function src_types()
	{
		return array(
			self::CTX => __('Context'),
			self::GET => __('GET array'),
			self::POST => __('POST array'),
			self::COOKIE => __('Cookie'),
			self::SESSION => __('Session'),
		);
	}
	
	public function value_types()
	{
		return array(
			self::TXT => __('Plain text'),
			self::HTML => __('HTML')
		);
	}
	
	public function get_code()
	{
		return 'wiget_sendmail_' . $this->id;
	}

	public function set_values(array $data)
	{
		$data['fields'] = array();
		if(!empty($data['field']) AND is_array( $data['field'] ))
		{
			foreach($data['field'] as $key => $values)
			{
				foreach($values as $index => $value )
				{
					if($index == 0)	continue;

					if($key == 'source')
					{
						$value = URL::title($value, '_');
					}

					$data['fields'][$index][$key] = $value;
				}
			}

			$data['field'] = NULL;
		}

		$email_type = ORM::factory('email_type', array('code' => $this->get_code()));
		
		if(!$email_type->loaded())
		{
			$email_type->values(array(
				'code' => $this->get_code(),
				'name' => $this->name
			))->create();
		}
		
		$email_type_fields = array();
		foreach ($data['fields'] as $field)
		{
			$email_type_fields['key'][] = $field['id'];
			$email_type_fields['name'][] = Inflector::humanize($field['id']);
		}

		$email_type->set('data', $email_type_fields)->update();
		
		return parent::set_values($data);
	}

	public function fetch_data()
	{
		return array();
	}

	public function on_page_load() 
	{
		$this->_errors = array();
		
		$this->_fetch_template();

		$this->_fetch_fields();
		
		if( !empty($this->_errors))
		{
			Flash::set('errors', $this->_errors);
			Flash::set('messages', $this->_messages);
			
			$query = URL::query(array('status' => 'error'), FALSE);
		} 
		else if( $this->send_message() )
		{
			$query = URL::query(array('status' => 'ok'), FALSE);
		}
		else
		{
			$query = URL::query(array('status' => 'error'), FALSE);
		}
		
		$referrer = Request::current()->referrer();
		HTTP::redirect( preg_replace('/\?.*/', '', $referrer) . $query, 302);
	}

	public function send_message()
	{
		return Email_Type::get($this->get_code())->send($this->_messages);
	}

	protected function _fetch_fields( ) 
	{
		foreach($this->fields as $field) 
		{
			$value = $this->_get_field_value($field);
			if(!empty($field['validator']))
			{
				if(  strpos( $field['validator'], '::' ) !== FALSE )
				{
					list($class, $method) = explode('::', $field['validator']);
				}
				else
				{
					$class = 'Valid';
					$method = $field['validator'];
				}
				
				if(method_exists($class, $method))
				{
					if(call_user_func_array($class . '::' . $method, array($value)))
					{
						$this->_messages[$field['id']] = $value;
					}
					else
					{
						$message = Kohana::message('validation', $method);
						$this->_errors[$field['id']] = array(
							'value' => $value,
							'error' => !empty($field['error']) ? $field['error'] : __($message, array(':field' => $field['id']))
						);
					}
				}
				else if(preg_match($field['validator'], $value))
				{
					$this->_messages[$field['id']] = $value;
				}
				else 
				{
					$this->_errors[$field['id']] = array(
						'value' => $value,
						'error' => !empty($field['error']) ? $field['error'] : NULL
					);
				}
			} 
			else
				$this->_messages[$field['id']] = $value;

			unset($field);
		}
	}

	protected function _get_field_value( $field ) 
	{
		
		$key = $field['id'];
		$value = NULL;

		$src = $field['source'] % 10;
		switch($src) 
		{
			case self::CTX:
				$value = Context::instance()->get($key);
				break;
			case self::GET:
				$value = Request::current()->query($key);	
				break;
			case self::POST:
				$value = Request::current()->post($key);	
				break;
			case self::COOKIE:
				$value = Cookie::get($key);	
				break;
			case self::SESSION:
				$value = Session::instance()->get($key);
		}

		switch($field['type']) 
		{
			case self::HTML:	
				$value = Kses::filter( $value, $this->allowed_tags );
				break;
			case self::TXT:		
				$value = HTML::chars($value);
		}

		return $value;
	}
	
	public function render( $params = array( ) )
	{
		return NULL;
	}
}