<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('datasource_after_remove', function($id) {
	DB::update('dshfields')
		->set(array('from_ds' => 0))
		->where('from_ds', '=', (int) $id)
		->execute();
});
Observer::observe('view_setting_plugins', function($plugin) {
	echo View::factory('datasource/hybrid/settings_page', array('plugin' => $plugin));
}, $plugin);

Observer::observe('save_settings', function($post, $plugin) {
	$post = Request::current()->post();

	if ( ! empty($post['plugin']['user_profile_ds_id']))
	{
		$profile_ds_id = $post['plugin']['user_profile_ds_id'];
		
		$ds = Datasource_Section::load($profile_ds_id);
		if ($ds === NULL OR $ds->type() != 'hybrid')
		{
			return;
		}
		
		$plugin->set('user_profile_ds_id', $profile_ds_id);
		$plugin->save_settings();
	}

}, $plugin);

Observer::observe(array('user_after_update', 'user_after_add'), function($user, $plugin) {
	$ds_id = $plugin->get('user_profile_ds_id');
	
	$ds = Datasource_Section::load($ds_id);
	if ($ds === NULL OR $ds->type() != 'hybrid')
	{
		return;
	}

	$doc = $ds->get_empty_document()->load_by('f_profile_id', $user->id);
	
	$doc->read_values(array(
		'header' => $user->username,
		'meta_title' => $user->username,
		'meta_keywords' => '',
		'meta_description' => '',
		'f_profile_id' => $user->id,
		'published' => TRUE
	));

	if( $doc->loaded() )
	{
		$ds->update_document($doc);
	}
	else
	{
		$doc = $ds->create_document($doc);
	}	
}, $plugin);