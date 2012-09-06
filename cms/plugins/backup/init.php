<?php defined('SYSPATH') or die('No direct access allowed.');

define('BACKUP_PLUGIN_FOLDER', DOCROOT . 'backups' . DIRECTORY_SEPARATOR);
 
if(!is_dir(BACKUP_PLUGIN_FOLDER))
{
	 mkdir(BACKUP_PLUGIN_FOLDER, 0775);
}