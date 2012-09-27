<?php defined('SYSPATH') or die('No direct access allowed.');

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'maintenance',
	'title' => 'Maintenance mode'
) )->register();

if($plugin->enabled())
{	
	if(IS_BACKEND)
	{
		Behavior::add('maintenance_mode', '');
		
		Observer::observe('view_setting_plugins', 'behavior_maintenance_mode_settings_page', $plugin);
		Observer::observe('save_settings', 'behavior_maintenance_mode_settings_save', $plugin);
	}
	else
	{
		if($plugin->enable_maintenance_mode == 'yes')
		{
			// Observe
			Observer::observe('frontpage_requested', 'behavior_maintenance_mode');
		}
	}
}

function behavior_maintenance_mode_settings_save( $post, $plugin )
{
	if(!isset($post['plugin']['enable_maintenance_mode']))
	{
		$post['plugin']['enable_maintenance_mode'] = 'no';
	}
	
	Model_Plugin::set_setting('enable_maintenance_mode', $post['plugin']['enable_maintenance_mode'], $plugin->id);
}

function behavior_maintenance_mode_settings_page( $plugin )
{
	echo View::factory('maintenance/settings', array(
		'plugin' => $plugin
	));
}

function behavior_maintenance_mode()
{
	$page = DB::select()
		->from(Page::TABLE_NAME)
		->where('behavior_id', '=', 'maintenance_mode')
		->limit(1)
		->as_object()
		->execute()
		->current();

	if ($page)
	{
		$page = FrontPage::find( $page->slug );

		// if we fund it, display it!
		if( is_object($page) )
		{
			echo $page->render_layout();
			exit(); // need to exit here otherwise the true error page will be sended
		}
	} 
	else 
	{
		throw new HTTP_Exception_404( 'Maintenance mode' );
		exit();
	}
}