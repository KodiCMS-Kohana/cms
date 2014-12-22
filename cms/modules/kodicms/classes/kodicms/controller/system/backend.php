<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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

		if ($this->auto_render !== TRUE)
		{
			return;
		}

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
			$this->template->breadcrumbs = Config::get('site', 'breadcrumbs') == Config::YES ? $this->breadcrumbs : NULL;
			$this->template->footer = View::factory('system/blocks/footer');
		}

		$this->template->theme = 'theme-' . Model_User_Meta::get('admin_theme', Config::get('global', 'default_theme'));
		$this->template->bind_global('navigation', $navigation);
		Observer::notify('controller_before_' . $this->get_path());
	}
	
	public function init_media()
	{
		parent::init_media();
		
		Assets::package(array(
			'jquery', 'bootstrap', 'notify', 'select2', 'dropzone', 'fancybox', 'datepicker', 'underscore', 'core'
		));

		foreach (array('.js', '-message.js') as $file_name)
		{
			if (file_exists(CMSPATH . FileSystem::normalize_path('media/js/i18n/' . I18n::lang() . $file_name)))
			{
				Assets::js('i18n', ADMIN_RESOURCES . 'js/i18n/' . I18n::lang() . $file_name, 'global', FALSE, 0);
			}
		}

		$file = $this->request->controller();
		$directory = $this->request->directory();
		if(!empty($directory))
		{
			$file = $directory . '/' . $file;
		}
		$file = strtolower($file);
		if (Kohana::find_file('media', FileSystem::normalize_path('js/controller/' . $file), 'js'))
		{
			Assets::js('controller.' . $file, ADMIN_RESOURCES . 'js/controller/' . $file . '.js', 'global', FALSE, 999);
		}

		Assets::group('global', 'events', '<script type="text/javascript">' . Assets::merge_files('js/events', 'js') . '</script>', 'global');
	}
}