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
 
 $controller = Dispatcher::getController();
 $action = Dispatcher::getAction();
 
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title><?php echo __('Login') .' &ndash; '. Setting::get('admin_title'); ?></title>
		
		<base href="<?php echo BASE_URL.ADMIN_DIR_NAME.'/'; ?>" />
		
		<link href="<?php echo BASE_URL.ADMIN_DIR_NAME; ?>/favicon.ico" rel="favourites icon" />
		
		<link href="stylesheets/login.css" rel="stylesheet" type="text/css" charset="utf-8" />
		<link href="themes/<?php echo Setting::get('theme'); ?>/login.css" id="css_theme" media="screen" rel="stylesheet" type="text/css" charset="utf-8" />
		
		<script src="javascripts/jquery-1.6.4.js"></script>
		
		<script>
			$(document).ready(function()
			{
				$('.message').animate({top: 0}, 1000);
				
				$('#noscript').hide();
				
				$('#badBrowserSkip').click(function()
				{
					$('#badBrowser').hide();
					
					return false;
				});
				
				if ( !$.support 
				     || (!$.browser.msie && !$.browser.webkit && !$.browser.mozilla && !$.browser.opera)
					 || ($.browser.mozilla && $.browser.version.slice(0,3) < 1.9)
					 || ($.browser.msie && $.browser.version < 7)
					 || ($.browser.opera && $.browser.version < 10)
				)
				{
					$('#badBrowser').show();
				}
				
				$('form input[type="text"]:first').focus();
			});
		</script>
		
	</head>
	<body id="body_<?php echo($controller .'_'. $action); ?>">

		<?php if( ($info = Flash::get('notice')) !== null ): ?><div id="noticeMessage" class="message"><?php echo $notice; ?></div><?php endif; ?>
		<?php if( ($error = Flash::get('error')) !== null ): ?><div id="errorMessage" class="message"><?php echo $error; ?></div><?php endif; ?>
		<?php if( ($success = Flash::get('success')) !== null ): ?><div id="successMessage" class="message"><?php echo $success; ?></div><?php endif; ?>
		
		<?php echo $content_for_layout; ?>
		
		<p id="website"><small><?php echo __('Website'); ?>: <a href="<?php echo CMS_URL; ?>"><?php echo CMS_URL; ?></a></small></p>
		
		<noscript id="noscript">
			<p><?php echo __('JavaScript is switched off or not supported in your internet browser. Please switch on JavaScript or use other browser. Thanks.'); ?></p>
		</noscript>
		
		<div id="badBrowser">
		<div id="badBrowserIn">
			<p><?php echo __('Your browser is not supported this CMS version. Please, use <a href="http://www.mozilla.com/">Mozilla Firefox 3+</a>, <a href="http://www.apple.com/safari/">Apple Safary</a>, <a href="http://www.google.com/chrome">Google Chrome</a>, <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home">Microsoft Internet Explorer</a> 7+ or <a href="http://opera.com/">Opera</a> 10+ version.'); ?></p>
			<p><button id="badBrowserSkip"><?php echo __('Skip this message'); ?></button></p>
		</div>
		</div>
	</body>
</html>