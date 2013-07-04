<?php defined('SYSPATH') or die('No direct access allowed.');

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

	public $fields = array();

	protected $_data = array(
		'allowed_tags' => '<b><i><u><p>',
		'from' => '{email}',
		'is_html' => 1
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

	public function set_values(array $data)
	{
		$data['is_html'] = empty($data['is_html']) ? FALSE : (bool) $data['is_html'];

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

		if(!($this->from AND $this->to AND $this->subject AND $this->template))
			return;

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
		$this->template = $this->_fetch_render(array());

		$this->from = $this->proccess_pattern($this->from);
		$this->to = $this->proccess_pattern($this->to);
		$this->subject = $this->proccess_pattern($this->subject);

		$this->template
			->set('from', $this->from)
			->set('to', $this->to)
			->set('subject', $this->subject)
			->set('data', $this->_messages );

		$email = Email::factory($this->subject)
			->from($this->from)
			->to($this->to)
			->message((string) $this->template, $this->is_html ? 'text/html' : 'text/plain');

		if( !empty($this->to_cc) )
			$email->cc( $this->to_cc );

		if( !empty($this->to_bcc) )
			$email->cc( $this->to_bcc );

		return $email->send();
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

		if($this->is_html)
		{
			switch($type = $field['type'] - $src) 
			{
				case self::HTML:	
					$value = Kses::filter( $value, $this->allowed_tags );
					break;
				case self::TXT:		
					$value = HTML::chars($value);
			}
		}

		return $value;
	}

	public function proccess_pattern($pattern) 
	{
		$fields = array();

		if($found = preg_match_all('/(?<!\\{)\\{([A-Za-z_\\.][A-Za-z0-9_\\.]*)\\}(?!\\})/u', $pattern, $fields)) 
		{
			$fields = array_unique($fields[1]);
			foreach($fields as $i => $field) 
			{
				$patterns[] = '/(?<!\\{)\\{'.preg_quote($field).'\\}(?!\\})/u';
				$replaces[] = Arr::get($this->_messages, $field);
			}

			$pattern = preg_replace($patterns, $replaces, $pattern);
		}

		return $pattern;
	}
	
	public function render( $params = array( ) )
	{
		return NULL;
	}
}