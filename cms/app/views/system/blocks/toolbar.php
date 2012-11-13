<?php 
Observer::notify('front_page_toolbar');

if(Setting::get( 'profiling' ) == 'yes')
{
	echo View::factory( 'profiler/stats' );
}
?>