<?php defined('SYSPATH') or die('No direct access allowed.');

$plugin = Plugins_Item::factory( array(
	'id' => 'backup',
	'title' => 'Backup DB'
) )->register();

if($plugin->enabled())
{
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
	
	Sheduler::add(function($from, $to) {
		$color = 'green';
		$data = array();
		$handle = opendir(BACKUP_PLUGIN_FOLDER);
		while (false !== ($file = readdir($handle))) 
		{
			if (!preg_match("/(sql|zip)/", $file, $m)) 
			{
				continue;
			}
			
			$created = filectime(BACKUP_PLUGIN_FOLDER . $file);

			if($from <= $created AND $to >= $created)
			{
				$data[] = array(
					'title' => 'Backup::(' . $file . ')',
					'start' => $created,
					'url' => URL::backend('/backup/view/' . $file),
					'color' => $color,
					'allDay' => FALSE
				);
			}
		}

		closedir($handle);
		
		return $data;
	});
}