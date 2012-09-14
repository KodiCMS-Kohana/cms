<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'googleanalytics',
	'title' => 'Google Analytics',
	'description' => 'Аналитика посещения пользователями сайта',
	'version' => '1.0.0',
	'settings' => TRUE
) )->register();

if ( $plugin->enabled() )
{
	Observer::observe( 'page_layout_bottom', 'enable_google_analytics', $plugin );
}

function enable_google_analytics( $plugin )
{
	echo View::factory( 'googleanalytics/footer' , array(
		'plugin' => $plugin
	) );
}