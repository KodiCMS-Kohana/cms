<?php defined('SYSPATH') or die('No direct access allowed.');

Kohana::load(PLUGPATH.'/less/vendors/lessc.inc.php');

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'less',
	'title' => 'LESS Compiler',
	'description' => 'LESS extends CSS with dynamic behavior such as variables, mixins, operations and functions.',
	'settings' => TRUE
) )->register();

if($plugin->enabled())
{	
	if(!IS_BACKEND)
	{
		less_compile();
	}
}


function less_compile() {
	try
	{
		$less_folder_path = trim(Plugins::getSetting('less_folder_path', 'less', 'public/less'), '/');
		$css_folder_path = trim(Plugins::getSetting('css_folder_path', 'less', 'public/css'), '/');
				
		$less_path = SYSPATH.$less_folder_path.DIRECTORY_SEPARATOR;
		$css_path = SYSPATH.$css_folder_path.DIRECTORY_SEPARATOR;
		
		
		if((!is_dir($less_path) AND !is_dir($css_path)) OR Plugins::getSetting('enabled', 'less') == 'no')
		{
			return;
		}

		$files = new Filesystem($less_path);

		$params = array();
		if(Plugins::getSetting('format_css', 'less', 'no') == 'no')
		{
			$params = array(
				'newlineChar' => '',
				'indentChar' => '',
			);
		}

		foreach ($files as $file)
		{
			if ($file->isDot() OR $file->isDir() OR $file->getFilenameUTF8() !== 'common.less') continue;

			$pathinfo = pathinfo($file->getFilenameUTF8() );

			if($pathinfo['extension'] == 'less')
			{
				lessc::ccompile( $less_path.$file->getFilenameUTF8(), $css_path.DIRECTORY_SEPARATOR.$pathinfo['filename'].'.css', $params);
			}
		}
	}
	catch ( Exception $e ) 
	{
		throw new Exception($e);
	}
}

