<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_System_Template extends Controller_System_Security
{
	/**
	 * @var  View  page template
	 */
	public $template = 'layouts/backend';

	/**
	 * @var  boolean  auto render template
	 **/
	public $auto_render = TRUE;
	
	public $json = NULL;
	
	public $styles = array();
	public $scripts = array();

	/**
	 * Loads the template [View] object.
	 */
	public function before()
	{
		parent::before();
		
		if($this->request->method() === Request::POST)
		{
//			$token = Arr::get($_POST, 'token');
//			if(empty($token) OR !Security::check($token))
//			{
//				throw new Exception('Security token not check');
//			}
		}

		if ($this->auto_render === TRUE)
		{
			if ( $this->request->is_ajax() === TRUE )
			{
				// Load the template
				$this->template = View::factory( 'layouts/ajax' );
			}
			else
			{
				$this->template = View::factory( $this->template );
			}
			
			// Initialize empty values
			$this->template->title = '';
			$this->template->content = '';

			$this->template->styles = array();
			$this->template->scripts = array();
		}
	}
	
	/**
	 * Assigns the template [View] as the request response.
	 */
	public function after()
	{
		parent::after();

		if ($this->auto_render === TRUE)
		{
			$this->template->styles = array_merge( $this->styles, $this->template->styles );
			$this->template->scripts = array_merge( $this->scripts, $this->template->scripts );

			$this->template->messages = View::factory('layouts/blocks/messages', array(
				'messages' => Messages::get() 
			));
			
			$this->template->modal = View::factory('layouts/blocks/modal');

			$this->response->body( $this->template );
		}
		elseif ( $this->request->is_ajax() === TRUE OR $this->json !== NULL)
		{
			if ( $this->json !== NULL )
			{
				if ( is_array( $this->json ) AND !isset( $this->json['status'] ) )
				{
					$this->json['status'] = TRUE;
				}

				$this->request
					->response()
					->headers( 'Content-type', 'application/json' );

				$this->template = json_encode( $this->json );
			}

			$this->response->body( $this->template );
		}
	}
}