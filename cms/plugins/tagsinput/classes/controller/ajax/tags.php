<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_Ajax_Tags extends Controller_Ajax_JSON {

	public function action_get()
	{
		$name = Arr::get($_GET, 'term');
		
		if(empty($name))
		{
			return NULL;
		}

		$tags = Record::findAllFrom('Tag', 'name LIKE "%:name%" ORDER BY count DESC', array(
			':name' => DB::expr($name)
		));
		
		$array = array();
		
		foreach ( $tags as $tag )
		{
			$array[]['value'] = $tag->name;
		}
		
		$this->json = $array;
	}

}