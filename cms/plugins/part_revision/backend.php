<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('part_before_save', function($part) {
	if (!$part->loaded())
	{
		return;
	}
	
	$data = array(
		'part_id' => $part->id, 
		'created_on' => date('Y-m-d H:i:s'),
		'content' => Arr::get($part->original_values(), 'content')
	);
	DB::insert('part_revision')
		->columns(array_keys($data))
		->values($data)
		->execute();
});

Observer::observe('part_controls', function() {
	$url = Route::get('backend')->uri(array(
		'controller' => 'part',
		'action' => 'revision',
		'id' => '<%=id%>'
	));
	
	echo '<a class="part-revision-button btn btn-default btn-xs" href="'.URL::site($url).'">'.__('Part revision').'</a>';
});