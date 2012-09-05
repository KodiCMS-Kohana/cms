<?php defined('SYSPATH') or die('No direct access allowed.');

define('BACKUP_PLUGIN_FOLDER', PLGPATH . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR);
 
if(!is_dir(BACKUP_PLUGIN_FOLDER))
{
	 mkdir(BACKUP_PLUGIN_FOLDER, 0775);
}