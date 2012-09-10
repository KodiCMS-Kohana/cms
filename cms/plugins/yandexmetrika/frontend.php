<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe( 'page_layout_bottom', 'enable_yandex_metrika' );

function enable_yandex_metrika( )
{
	echo View::factory( 'yandex_metrika/footer');
}