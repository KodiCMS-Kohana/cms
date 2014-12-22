<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Elfinder
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Elfinder extends Controller_System_Api {
	
	public function before()
	{
		parent::before();
		
		include_once Kohana::find_file('media', 'libs/elfinder/php/elFinderConnector.class');
		include_once Kohana::find_file('media', 'libs/elfinder/php/elFinder.class');
		include_once Kohana::find_file('media', 'libs/elfinder/php/elFinderVolumeDriver.class');
		include_once Kohana::find_file('media', 'libs/elfinder/php/elFinderVolumeLocalFileSystem.class');
		include_once Kohana::find_file('media', 'libs/elfinder/php/elFinderVolumeMySQL.class');
				
		$opts = array(
			'roots' => Config::get('elfinder', 'volumes')
		);
		
		$this->json = NULL;

		// run elFinder
		$connector = new elFinderConnector(new elFinder($opts));
		$connector->run();
	}
	
	public function rest_get()
	{
		
	}
	
	public function rest_put()
	{
		
	}
	
	public function rest_delete()
	{
		
	}
}

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