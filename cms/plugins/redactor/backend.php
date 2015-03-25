<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe('modules::after_load', function() {
	WYSIWYG::add('redactor', 'RedactorJS');
});