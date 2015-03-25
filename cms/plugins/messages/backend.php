<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('view_navbar_menu', function() {
	echo View::factory('messages/navbar');
});