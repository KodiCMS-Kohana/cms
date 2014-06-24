<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('datasource_after_remove', function($id) {
	DB::update('dshfields')
		->set(array('from_ds' => 0))
		->where('from_ds', '=', (int) $id)
		->execute();
});