<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('datasource_after_remove', function($id) {
	DB::update('dshfields')
		->set(array('from_ds' => 0))
		->where('from_ds', '=', (int) $id)
		->execute();
});

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

	if ($doc->loaded())
	{
		$ds->update_document($doc);
	}
	else
	{
		$doc = $ds->create_document($doc);
	}	
}, $plugin);

Observer::observe('user_after_delete', function($id, $plugin) {
	
	$ds_id = $plugin->get('user_profile_ds_id');
	
	$ds = Datasource_Section::load($ds_id);
	if ($ds === NULL OR $ds->type() != 'hybrid')
	{
		return;
	}
	
	$doc = $ds->get_empty_document()->load_by('f_profile_id', $id);
	
	if ($doc->loaded())
	{
		$ds->remove_documents(array($doc->id));
	}
}, $plugin);

Observer::observe('view_user_profile_sidebar_list', function($id, $plugin) {
	
	$ds_id = $plugin->get('user_profile_ds_id');

	$ds = Datasource_Section::load($ds_id);
	if ($ds === NULL OR $ds->type() != 'hybrid')
	{
		return;
	}
	
	$doc = $ds->get_empty_document()->load_by('f_profile_id', $id);

	if ($doc->loaded())
	{
		echo View::factory('datasource/hybrid/user_profile', array(
			'fields' => $ds->record()->fields(),
			'document' => $doc,
			'header' => $ds->name
		));
	}
}, $plugin);

Observer::observe('datasource_after_remove', function($id, $plugin) {
	
	$ds_id = $plugin->get('user_profile_ds_id');
	if($ds_id == $id)
	{
		unset($plugin->user_profile_ds_id);
		$plugin->save_settings();
	}
	
}, $plugin);