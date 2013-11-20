<?php 
	Observer::notify('front_page_toolbar');

	if(Config::get('site', 'profiling' ) == 'yes')
	{
		echo View::factory( 'profiler/stats' );
	}
?>