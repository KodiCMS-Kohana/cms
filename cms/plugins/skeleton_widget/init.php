<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugin::factory('skeleton_widget', array(
	'title' => 'Skeleton Widget',
	'version' => '1.0.0',
	'description' => 'Заготовка для создания плагина виджета.',
	'author' => 'KodiCMS',
	'required_cms_version' => '100.0.0'
))->register();