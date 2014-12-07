<?php defined('SYSPATH') or die('No direct access allowed.');

define('PLUGIN_HYBRID_PATH', PLUGPATH . 'hybrid' . DIRECTORY_SEPARATOR);
define('PLUGIN_HYBRID_URL', PLUGINS_URL . 'hybrid/media/');

Plugin::factory('hybrid', array(
	'title' => 'Hybrid Datasource',
	'author' => 'ButscH'
))->register();
