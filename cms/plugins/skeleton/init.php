<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

define('PLUGIN_SKELETON_PATH', PLUGPATH . 'skeleton' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
define('PLUGIN_SKELETON_URL', PLUGINS_URL . 'skeleton/public/');
  
// Этот файл подключается всегда после активации плагина и на странице списка плагинов
// даже если плагин не активирован

$plugin = Plugin::factory('skeleton', array(
	'title' => 'Skeleton',
	'version' => '1.0.0',
	'description' => 'Заготовка для создания плагина. Не советуется активировать',
	'author' => 'KodiCMS',
	'required_cms_version' => '100.0.0' // Требуемая версия CMS
))->register();

//	if($plugin->is_activated())
//	{
//		... 
//	}

Assets_Package::add('skeleton');

/**
 * Создание media пакета, для быстрого подключения через виджет 
 * или через класс Meta 
 *
 *		Assets_Package::add('skeleton')
 *			->css(NULL, PLUGINS_URL . 'skeleton/media/css/skeleton.css')
 *			->js(NULL, PLUGINS_URL . 'skeleton/media/js/skeleton.js', 'jquery');
 *		
 * ИЛИ
 *		Assets_Package::add('skeleton')
 *			->css(NULL, ADMIN_RESOURCES . 'css/skeleton.css')
 *			->js(NULL, ADMIN_RESOURCES . 'js/skeleton.js', 'jquery');
 * 
 * ИЛИ дополнить существующий
 * 
 *		Assets_Package::load('jquery')
 *			->js(....);
 * 
 */