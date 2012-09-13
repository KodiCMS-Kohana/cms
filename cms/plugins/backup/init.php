<?php defined('SYSPATH') or die('No direct access allowed.');

define('BACKUP_PLUGIN_FOLDER', DOCROOT . 'backups' . DIRECTORY_SEPARATOR);

Route::set( 'backup', ADMIN_DIR_NAME.'/backup(/<action>(/<file>))', array(
	'action' => '(view|delete|restore)',
	'file' => '.*'
) )
	->defaults( array(
		'controller' => 'backup'
	) );
 
if(!is_dir(BACKUP_PLUGIN_FOLDER))
{
	 mkdir(BACKUP_PLUGIN_FOLDER, 0775);
}