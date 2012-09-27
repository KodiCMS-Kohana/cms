<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_System_Plugin extends Controller_Plugins {
	
	public $plugin;
	
	public $plugin_id = NULL;


	public function before()
	{
		parent::before();
		
		if($this->plugin_id === NULL)
		{
			$this->plugin_id = strtolower($this->request->controller());

			if ( !Model_Plugin::is_enabled( $this->plugin_id ) )
			{
				throw new Kohana_Exception( 'Plugin not activated' );
			}
		}
		
		$this->plugin = Model_Plugin::get_registered( $this->plugin_id );
	}
	
	public function display($view, $vars = NULL)
	{
		return View::factory($this->plugin->id . '/' . $view, $vars);
	}

	public function action_settings()
	{
		if ( $this->request->method() == Request::POST )
		{
			return $this->_settings_save( $this->plugin );
		}

		$this->template->content = View::factory('plugins/settings', array(
			'content' => View::factory($this->plugin->id.'/settings', array(
				'plugin' => $this->plugin
			))
		));
	}

	protected function _settings_save( $plugin )
	{
		$data = Arr::get( $_POST,  'setting' );
		Model_Plugin::set_all_settings( $data, $plugin->id );
		
		// save and quit or save and continue editing?
		if ( isset( $_POST['commit'] ) )
		{
			$this->go( URL::site( 'plugins' ) );
		}
		else
		{
			$this->go_back();
		}
	}
	
} // end PluginController class