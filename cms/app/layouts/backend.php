<?php if(!defined('CMS_ROOT')) die;

/**
 * Flexo CMS - Content Management System. <http://flexo.up.dn.ua>
 * Copyright (C) 2008 Maslakov Alexander <jmas.ukraine@gmail.com>
 * Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Flexo CMS.
 *
 * Flexo CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flexo CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flexo CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Flexo CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * @package Flexo
 * @subpackage layouts
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

$controller = Dispatcher::getController(Setting::get('default_tab'));
$action = Dispatcher::getAction();
$params = Dispatcher::getParams();

Plugin::addNav('Content',  __('Pages'),    'page',    array('administrator','developer','editor'), 100);
Plugin::addNav('Design',   __('Layouts'),  'layout',  array('administrator','developer'), 100);
Plugin::addNav('Design',   __('Snippets'), 'snippet', array('administrator','developer'), 100);
Plugin::addNav('Settings', __('General'),  'setting', array('administrator'), 100);
Plugin::addNav('Settings', __('Plugins'),  'plugins', array('administrator'), 100);
Plugin::addNav('Settings', __('Users'),    'user',    array('administrator'), 100);

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title><?php echo __(ucfirst($controller)); ?> &ndash; <?php echo Setting::get('admin_title'); ?></title>
		
		<base href="<?php echo BASE_URL.ADMIN_DIR_NAME.'/'; ?>" />
		
		<link href="<?php echo BASE_URL.ADMIN_DIR_NAME; ?>/favicon.ico" rel="favourites icon" />
		
		<link href="stylesheets/backend.css" media="screen" rel="stylesheet" type="text/css" charset="utf-8" />
		<link href="themes/<?php echo Setting::get('theme'); ?>/backend.css" id="css_theme" media="screen" rel="stylesheet" type="text/css" charset="utf-8" />
		
		<script>
			var BASE_URL         = '<?php echo BASE_URL; ?>';
			var CMS_URL          = '<?php echo CMS_URL; ?>';
			var ADMIN_DIR_NAME   = '<?php echo ADMIN_DIR_NAME; ?>';
			var PUBLIC_URL       = '<?php echo PUBLIC_URL; ?>';
			var PLUGINS_URL      = '<?php echo PLUGINS_URL; ?>';
			var LOCALE           = '<?php echo I18n::getLocale(); ?>';
		</script>
		
		<script src="javascripts/jquery-1.6.4.js"></script>
		
		<link href="javascripts/jquery-ui/jquery-ui-1.8.12.css" media="screen" rel="stylesheet" type="text/css" charset="utf-8" />
		<script src="javascripts/jquery-ui/jquery-ui-1.8.12.js"></script>
		
		<script src="javascripts/jquery.tubby-0.12.js"></script>
		
		<script src="javascripts/backend.js"></script>
		
<?php if (file_exists(CMS_ROOT.DIRECTORY_SEPARATOR.ADMIN_DIR_NAME.DIRECTORY_SEPARATOR.'javascripts'.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR. I18n::getLocale().'-message.js')): ?>
<script src="<?php echo BASE_URL.ADMIN_DIR_NAME; ?>/javascripts/i18n/<?php echo I18n::getLocale(); ?>-message.js"></script>
<?php endif; ?>

<!-- Plugins automatic requires -->
<?php foreach( Plugin::$plugins as $plugin_id => $plugin ): ?>
<?php if( file_exists(PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR. $plugin_id . '.css') ): ?><link href="<?php echo PLUGINS_URL.$plugin_id.'/'. $plugin_id.'.css'; ?>" media="screen" rel="stylesheet" type="text/css" charset="utf-8" /><?php endif; ?>
<?php if( file_exists(PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR. I18n::getLocale().'-message.js') ): ?><script src="<?php echo PLUGINS_URL.$plugin_id; ?>/i18n/<?php echo I18n::getLocale(); ?>-message.js"></script><?php endif; ?>
<?php if (file_exists(PLUGINS_ROOT.DIRECTORY_SEPARATOR.$plugin_id.DIRECTORY_SEPARATOR. $plugin_id .'.js')): ?><script src="<?php echo PLUGINS_URL.$plugin_id.'/'. $plugin_id.'.js'; ?>" type="text/javascript" charset="utf-8"></script><?php endif; ?>
<?php endforeach; ?>

<?php foreach( Plugin::$javascripts as $javascript ): ?><script src="<?php echo $javascript; ?>"></script><?php endforeach; ?>
<?php foreach( Plugin::$stylesheets as $stylesheet ): ?><link href="<?php echo $stylesheet; ?>" media="screen" rel="stylesheet" type="text/css" charset="utf-8" /><?php endforeach; ?>

<?php Observer::notify('layout_backend_head'); ?>

	</head>
	<body id="body_<?php echo $controller .'_'. $action . ($controller == 'plugin' ? '_'. (empty($params) ? 'index' : $params[0]) : ''); ?>">
		
		<div id="layout">
			
			<?php if( ($info = Flash::get('notice')) !== null ): ?><div id="noticeMessage" class="message"><?php echo $notice; ?></div><?php endif; ?>
			<?php if( ($error = Flash::get('error')) !== null ): ?><div id="errorMessage" class="message"><?php echo $error; ?></div><?php endif; ?>
			<?php if( ($success = Flash::get('success')) !== null ): ?><div id="successMessage" class="message"><?php echo $success; ?></div><?php endif; ?>
			
			<div id="navigation">
				<h1 id="siteName"><a href="<?php $default_tab = Setting::get('default_tab'); echo(get_url(empty($default_tab) ? 'page/index': $default_tab)); ?>"><?php echo Setting::get('admin_title'); ?></a></h1>
				
				<ul id="menu">
					<?php foreach( Plugin::getNav() as $nav_name => $nav ): ?>
					<li <?php if($nav->is_current) echo('class="current"'); ?> >
						<span><?php echo __($nav_name); ?></span>
						
						<ul>
							<?php foreach( $nav->items as $item ): ?>
							<li <?php if($item->is_current) echo('class="current"'); ?>><a href="<?php echo get_url($item->uri); ?>"><?php echo $item->name; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>
					<?php endforeach; ?>
				</ul>
				
				<div id="loggedUser"><?php echo __('You logged as'); ?> <a href="<?php echo get_url('user/edit/'.AuthUser::getId()); ?>"><?php echo AuthUser::getRecord()->name; ?></a> | <a href="<?php echo get_url('login/logout'); ?>"><?php echo __('Logout'); ?></a></div>
				<div id="siteView"><a href="<?php echo CMS_URL; ?>" target="_blank"><?php echo __('View Site'); ?></a></div>
			</div><!-- /#navigation -->
			
			<div id="main">
				<?php if(isset($sidebar)): ?>
				<div id="sidebar">
					
					<?php echo $sidebar; ?>
					
				</div><!--/#sidebar-->
				<?php endif; ?>
				
				<div id="content" <?php if(isset($sidebar)) echo('class="content-has-sideber"'); ?> >
					
					<?php echo $content_for_layout; ?>
					
				</div> <!--/#content-->
			</div> <!-- /#main -->
			
			<div id="copyrights">
				&copy; Flexo CMS v<?php echo CMS_VERSION; ?> | <a href="http://flexo.up.dn.ua/" target="_blank"><?php echo __('Information'); ?></a>
			</div>
			
		</div><!--/#layout-->
		
		<noscript id="noscript">
			<p><?php echo __('JavaScript is switched off or not supported in your Internet Browser. Please switch on JavaScript or change your Browser.'); ?></p>
		</noscript>
		
	</body>
</html>
<!--<?php echo execution_time(); ?> -->