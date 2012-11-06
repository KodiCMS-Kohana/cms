<?php 
Observer::notify('front_page_toolbar');

if(Setting::get( 'profiling' ) == 'yes')
{
	$inject_html .= (string) View::factory( 'profiler/stats' );
}
?>