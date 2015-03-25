<?php defined('SYSPATH') or die('No direct script access.');

 if (ACL::check('email.settings'))
{
	if (ACL::check('email.settings'))
	{
		Observer::observe('view_setting_plugins', function() {
			echo View::factory('email/queue/settings');
		});

		Observer::observe('validation_settings', function( $validation, $filter ) {
			$filter
				->rule('email_queue.batch_size', 'intval')
				->rule('email_queue.interval', 'intval')
				->rule('email_queue.max_attempts', 'intval');
		});
	}
}