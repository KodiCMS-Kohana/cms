<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_System_Template extends Controller_System_Security
{
	/**
	 * @var  View  page template
	 */
	public $template = 'system/backend';

	/**
	 *
	 * @var \Breadcrumbs 
	 */
	public $breadcrumbs;
	
	/**
	 *
	 * @var array 
	 */
	public $template_js_params = array();

	/**
	 * @var  boolean  auto render template
	 **/
	public $auto_render = TRUE;
	
	/**
	 *
	 * @var boolean
	 */
	public $only_content = FALSE;
	
	/**
	 *
	 * @var mixed
	 */
	public $json = NULL;

	/**
	 * Loads the template [View] object.
	 */
	public function before()
	{
		parent::before();

		if ($this->auto_render === TRUE)
		{
			if ($this->request->is_ajax() === TRUE)
			{
				// Load the template
				$this->template = View::factory('system/ajax');
			}
			else
			{
				$this->template = View::factory($this->template);
			}

			// Initialize empty values
			$this->template->title = NULL;
			$this->template->content = NULL;

			$this->breadcrumbs = Breadcrumbs::factory();

			$routes = Route::all();
			if (isset($routes['backend']))
			{
				$this->breadcrumbs
					->add(UI::icon('home'), Route::get('backend')->uri());
			}
			
			$this->init_media();
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
			if ($this->request->is_ajax() === TRUE OR $this->json !== NULL)
			{
				if ($this->json !== NULL)
				{
					if (is_array($this->json) AND ! isset($this->json['status']))
					{
						$this->json['status'] = TRUE;
					}

					$this->response->headers('Content-type', 'application/json');

					$this->template = json_encode($this->json);
				}
				else
				{
					$this->only_content = TRUE;
				}
			}
			else
			{
				$js_string = '';
				foreach ($this->template_js_params as $var => $value)
				{
					$value = json_encode($value);
			
					$js_string .= "var {$var} = {$value};\n";
				}
				
				Assets::group('global', 'js_params', '<script type="text/javascript">' . $js_string . '</script>', 'global');
			}

			if ($this->only_content)
			{
				$this->template = $this->template->content;
			}

			if ($this->template instanceof View)
			{
				$this->template->set('request', $this->request);
			}

			Observer::notify('template_before_render', $this->request);
			$this->response->body($this->template);
		}
	}
	
	
	/**
	 * 
	 * @param string $separator
	 * @return string
	 */
	public function get_path($separator = '_')
	{
		$path = $this->request->controller() . $separator . $this->request->action();
		$dir = $this->request->directory();

		if (!empty($dir))
		{
			$path = $dir . $separator . $path;
		}

		return strtolower($path);
	}
	
	/**
	 * 
	 * @param string $title
	 * @param boolean $set_breadcrumbs
	 * @return Controller
	 */
	public function set_title($title, $set_breadcrumbs = TRUE)
	{
		$this->template->title = $title;

		if ($set_breadcrumbs === TRUE)
		{
			$this->breadcrumbs->add($title, FALSE, FALSE, 999);
		}

		return $this;
	}
	
	public function init_media()
	{
		$this->template_js_params = array(
			'CURRENT_URL' => Request::current()->url(TRUE) . URL::query(),
			'BASE_URL' => URL::backend(ADMIN_DIR_NAME, TRUE),
			'SITE_URL' => URL::base(TRUE),
			'ADMIN_DIR_NAME' => ADMIN_DIR_NAME,
			'ADMIN_RESOURCES' => ADMIN_RESOURCES,
			'PUBLIC_URL' => PUBLIC_URL,
			'LOCALE' => I18n::lang(),
			'CONTROLLER' => strtolower(Request::current()->controller()),
			'ACTION' => Request::current()->action(),
			'USER_ID' => Auth::get_id(),
			'FILTERS' => WYSIWYG::findAll(),
			'DATE_FORMAT' => Config::get('site', 'date_format'),
			'IS_BACKEND' => IS_BACKEND
		);

		foreach (Messages::get() as $type => $messages)
		{
			$this->template_js_params['MESSAGE_' . strtoupper($type)] = $messages;
		}
	}
}