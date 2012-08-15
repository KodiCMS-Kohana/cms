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
 * @subpackage views
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @author Maslakov Alexandr <jmas.ukraine@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Maslakov Alexander, 2011
 */

?>
<!DOCTYPE heml>
<html>
	<head>
		
		<title><?php echo __('Content Not Found'); ?> (404)</title>
		
		<style type="text/css">
			body {
				font-size: 95%;
				font-family: 'Liberation Sans', Helvetica, Arial, sans-serif;
				padding:10% 20% 0 20%;
				color:#000;
				background:yellow;
			}
			
			a {
				color:blue;
			}
			
			a:hover {
				color:red;
			}
			
			#message {
				background:#fff;
				border-radius:15px;
				-webkit-border-radius:15px;
				-moz-border-radius:15px;
				padding:15px 35px;
				box-shadow:0 1px 2px #000;
				-webkit-box-shadow:0 1px 2px #000;
				-moz-box-shadow:0 1px 2px #000;
			}
			
			#message h1 {
				margin:15px 0;
			}
		</style>
		
	</head>
	<body>
	
		<div id="message">
			<h1><?php echo __('Content Not Found'); ?> (404)</h1>
			<?php echo __('<p>The content you requested was not found. It may have been deleted or you may have entered an incorrect address.</p><p>Please return to the <a href=":base_url">home page</a> to view main navigation.</p>', array(':base_url' => BASE_URL)); ?>
		</div>
	
	</body>
</html>