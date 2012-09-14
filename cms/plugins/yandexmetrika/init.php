<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'yandexmetrika',
	'title' => 'Yandex Метрика',
	'description' => 'Метрика посещения пользователями сайта',
	'version' => '1.0.0',
	'settings' => TRUE
) )->register();

if ( $plugin->enabled() )
{
	Observer::observe( 'page_layout_bottom', 'enable_yandex_metrika', $plugin );
}

function enable_yandex_metrika( $plugin )
{
	echo View::factory( 'yandexmetrika/footer' , array(
		'plugin' => $plugin
	) );
}