<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		ButscHSter
 */
class KodiCMS_Controller_System_Backend extends Controller_System_Template
{
	public $auth_required = TRUE;

	/**
	 *
	 * @var Model_Navigation_Page 
	 */
	public $page;

	public function before()
	{
		$page = strtolower(substr(get_class($this), 11));
		
		Model_Navigation::init(Kohana::$config->load('sitemap')->as_array());

		parent::before();
		$navigation = Model_Navigation::get();
		$this->page = Model_Navigation::$current;
		
		if ($this->auto_render !== TRUE) return;

		$this->template->set_global(array(
			'page_body_id' => $this->get_path(),
			'page_name' => $page,
			'page' => $this->page
		));

		if ($this->request->is_iframe())
		{
			$navigation = NULL;
			$this->template->footer = NULL;
			$this->template->breadcrumbs = NULL;
			Config::set('site', 'profiling', 'no');

			$this->query_params = array('type' => 'iframe');
		}
		else
		{
			$this->template->breadcrumbs = Config::get('site', 'breadcrumbs' ) == Config::YES ? $this->breadcrumbs : NULL;
			$this->template->footer = View::factory('system/blocks/footer');
		}

		$this->template->bind_global('navigation', $navigation);

		Assets::package(array(
			'jquery', 'bootstrap', 'notify', 'select2', 'dropzone', 'fancybox', 'datepicker', 'underscore'
		));

		Assets::css('global', ADMIN_RESOURCES . 'css/common.css');

		Assets::js('core', ADMIN_RESOURCES . 'js/core.js', 'backbone');
		Assets::js('global', ADMIN_RESOURCES . 'js/backend.js', 'core');

		if (file_exists(CMSPATH . FileSystem::normalize_path('media/js/i18n/'.I18n::lang().'.js')))
		{
			Assets::js('i18n', ADMIN_RESOURCES . 'js/i18n/'.I18n::lang().'.js', 'global');
		}

		$file = strtolower($this->request->controller());
		if (Kohana::find_file('media', FileSystem::normalize_path('js/controller/' . $file), 'js'))
		{
			Assets::js('controller.' . $file, ADMIN_RESOURCES . 'js/controller/' . $file . '.js', 'global');
		}

		$cache = Cache::instance();
		$events_js_content = $cache->get('events_js');
		if($events_js_content === NULL)
		{
			$events_js_files = Kohana::find_file('media', FileSystem::normalize_path('js/events'), 'js', TRUE);
			if ( ! empty($events_js_files))
			{
				foreach ($events_js_files as $file)
				{
					$events_js_content .= file_get_contents($file) . "\n";
				}

				if(Kohana::$caching === TRUE)
				{
					$cache->set('events_js', $content, Date::DAY);
				}
			}
		}

		if ( ! empty($events_js_content))
		{
			Assets::group('global', 'events', '<script type="text/javascript">' . $events_js_content . '</script>', 'global');
		}

		Observer::notify('controller_before_' . $this->get_path());
	}
}