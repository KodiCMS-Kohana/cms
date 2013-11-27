<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('view_user_profile_sidebar_list', function($user_id) {
	if($user_id == AuthUser::getId())
	{
		$new = Api::get('user-messages.count_new', array(
			'uid' => AuthUser::getId()
		))->as_object();

		echo View::factory('messages/profile/sidebar', array(
			'new_messages' => (int) $new->response
		));
	}
});

Observer::observe('view_user_profile_toolbar', function($user_id) {
	echo View::factory('messages/profile/toolbar', array(
		'user_id' => (int) $user_id
	));
});

Observer::observe('layout_backend_head', function() {
	echo View::factory('messages/scripts');
});