<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugin::factory('skeleton_dashboard_widget', array(
	'title' => 'Skeleton Dashboard Widget',
	'version' => '1.0.0',
	'description' => 'Заготовка для создания плагина виджета для рабочего стола.',
	'author' => 'KodiCMS',
	'required_cms_version' => '100.0.0'
))->register();