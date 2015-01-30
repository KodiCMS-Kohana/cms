<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Plugins
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Plugins extends Controller_System_API
{	
	public function rest_get()
	{
		if (!ACL::check('plugins.index'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You don\'t have permission to :permission', array(
				':permission' => __('View plugin')
			));
		}

		$plugins = array();
		
		foreach (Plugins::find_all() as $plugin)
		{
			$plugins[] = $this->_get_info($plugin);
		}

		$this->response($plugins);
	}
	
	public function rest_put()
	{
		if (!ACL::check('plugins.change_status'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You don\'t have permission to :permission', array(
				':permission' => __('Install or uninstall plugin')
			));
		}

		Plugins::find_all();

		$plugin = Plugins::get_registered($this->param('id', NULL, TRUE));

		if (!$plugin->is_activated() AND (bool) $this->param('installed') === TRUE)
		{
			$plugin->activate();
		}
		else
		{
			$plugin->deactivate((bool) $this->param('remove_data'));
		}

		Kohana::$log->add(Log::INFO, ':user :action plugin :name', array(
			':action' => $plugin->is_activated() ? 'activate' : 'deactivate',
			':name' => $plugin->title()
		))->write();

		$this->response($this->_get_info($plugin));
	}
	
	public function get_repositories_list()
	{
		$response = Update::request('https://api.github.com/users/KodiCMS/repos');
		$response = json_decode($response, true);

		$local_plugins = array_keys(Plugins::find_all());
		$repo_plugins = array();
	
		foreach ($response as $repo)
		{
			if (strpos($repo['name'], 'plugin-') !== 0)
			{
				continue;
			}

			$replo_plugin_name = substr($repo['name'], strlen('plugin-'));

			$repo_plugins[] = array(
				'id' => $replo_plugin_name,
				'name' => ucfirst(Inflector::humanize($replo_plugin_name)),
				'description' => $repo['description'],
				'url' => $repo['html_url'],
				'clone_url' => $repo['clone_url'],
				'archive_url' => $repo['html_url'] . '/archive/' . $repo['default_branch'] . '.zip',
				'is_installed' => in_array($replo_plugin_name, $local_plugins),
				'is_new' => (time() - strtotime($repo['created_at'])) < Date::MONTH,
				'last_update' => Date::format(strtotime($repo['updated_at'])),
				'homepage' => $repo['homepage'],
				'plugin_path' => DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, array('cms', 'plugins', $replo_plugin_name)),
				'stars' => $repo['stargazers_count'],
				'watchers' => $repo['watchers_count']
			);
		}
		
		$this->response($repo_plugins);
	}

	protected function _get_info(Plugin $plugin)
	{
		return array(
			'id' => $plugin->id(),
			'title' => $plugin->title(),
			'description' => $plugin->description(),
			'version' => $plugin->version(),
			'author' => $plugin->author(),
			'installed' => $plugin->is_activated(),
			'settings' => $plugin->has_settings_page(),
			'icon' => $plugin->icon(),
			'required_cms_version' => $plugin->required_cms_version(),
			'is_installable' => $plugin->is_installable()
		);
	}
}
