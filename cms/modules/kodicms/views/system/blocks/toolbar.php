<?php 
	Observer::notify('front_page_toolbar');

	if(Config::get('site', 'profiling' ) == Config::YES)
	{
		echo View::factory( 'profiler/stats' );
	}
?>