<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Elfinder extends Controller_System_Plugin {
	
	public $plugin_id = 'elrte';
	
	public function action_index()
	{
		$path = PLUGPATH . DIRECTORY_SEPARATOR . 'elrte' 
			. DIRECTORY_SEPARATOR . 'vendors' 
			. DIRECTORY_SEPARATOR . 'elfinder'
			. DIRECTORY_SEPARATOR . 'connectors' 
			. DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR;
		
		include_once $path.'elFinder.class.php';

		/**
		 * Simple function to demonstrate how to control file access using "accessControl" callback.
		 * This method will disable accessing files/folders starting from  '.' (dot)
		 *
		 * @param  string  $attr  attribute name (read|write|locked|hidden)
		 * @param  string  $path  file path relative to volume root directory started with directory separator
		 * @return bool|null
		 **/
		function access($attr, $path, $data, $volume) {
			return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
				? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
				:  null;                                    // else elFinder decide it itself
		}

		$opts = array(
			'root'            => PUBLICPATH,
			'URL'             => PUBLIC_URL
		);

		// run elFinder
		$connector = new elFinder($opts);
		$connector->run();
	}
}