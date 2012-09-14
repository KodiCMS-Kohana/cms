<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'reformal',
	'title' => 'Reformal',
	'description' => 'Реформал – это простой и эффективный сервис обратной связи',
	'version' => '1.0.0',
	'settings' => TRUE
) )->register();

if ( $plugin->enabled() )
{
	Observer::observe( 'page_layout_bottom', 'enable_reformal', $plugin );
}

function enable_reformal( $plugin )
{
	echo View::factory( 'reformal/footer', array(
		'plugin' => $plugin
	) );
}