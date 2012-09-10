<?php defined('SYSPATH') or die('No direct script access.');

return array(
	// Plural test
	':count files'	=> array(
		'one' => ':count file',
		'other' => ':count files',
	),
	// Date/time
	'date' => array(
		'months' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
		'months_abbr' => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
		'days' => array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
		'days_abbr' => array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'),
		'order' => array('month', 'date', 'year'),	// Culture's date order: MM/DD/YYYY
		'short_date' => '%m/%d/%Y',
		'short_time' => '%I:%M%p',
		'am'	=> 'AM',
		'pm'	=> 'PM',
		'less_than_minute_ago' => 'less than a minute ago',
		'minute_ago' => array(
			'one'	=> 'about a minute ago',
			'other' => '{delta} minutes ago',
		),
		'hour_ago' => array(
			'one'	=> 'about an hour ago',
			'other' => 'about {delta} hours ago',
		),
		'day_ago' => array(
			'one'	=> '1 day ago',
			'other' => '{delta} days ago',
		),
		'week_ago' => array(
			'one'	=> '1 week ago',
			'other' => '{delta} weeks ago',
		),
		'month_ago' => array(
			'one'	=> '1 month ago',
			'other' => '{delta} months ago',
		),
		'year_ago' => array(
			'one'	=> '1 year ago',
			'other' => '{delta} years ago',
		),
		'less_than_minute_until' => 'less than a minute from now',
		'minute_until' => array(
			'one'	=> 'about a minute from now',
			'other' => '{delta} minutes from now',
		),
		'hour_until' => array(
			'one'	=> 'about an hour from now',
			'other' => 'about {delta} hours from now',
		),
		'day_until' => array(
			'one'	=> '1 day from now',
			'other' => '{delta} days from now',
		),
		'week_until' => array(
			'one'	=> '1 week from now',
			'other' => '{delta} weeks from now',
		),
		'month_until' => array(
			'one'	=> '1 month from now',
			'other' => '{delta} months from now',
		),
		'year_until' => array(
			'one'	=> '1 year from now',
			'other' => '{delta} years from now',
		),
		'never' => 'never',
	),
	'valid' => array(
		'decimal' => array(
			'one' => ':field must be a decimal with one place',
			'other' => ':field must be a decimal with :param2 places',
		),
		'exact_length' => array(
			'one' => ':field must be exactly one character long',
			'other' => ':field must be exactly :param2 characters long',
		),
		'max_length' => array(
			'other' => ':field must be less than :param2 characters long',
		),
		'min_length' => array(
			'one' => ':field must be at least one character long',
			'other' => ':field must be at least :param2 characters long',
		),
	),
);