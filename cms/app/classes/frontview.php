<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Acts as an object wrapper for HTML pages with embedded PHP, called "views".
 * Variables can be assigned with the view object and referenced locally within
 * the view.
 *
 * @package    Kohana
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class FrontView extends Kohana_View {
	
	public static function factory($file = NULL, array $data = NULL)
	{
		return new FrontView($file, $data);
	}
	
	public function render_html($html)
	{
		// Combine local and global data and capture the output
		return FrontView::capture_html($html, $this->_data);
	}
	
	protected static function capture_html($html, array $view_data)
	{
		// Import the view variables to local namespace
		extract($view_data, EXTR_SKIP);

		// Capture the view output
		ob_start();

		try
		{
			eval('?>' . $html);
		}
		catch (Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}

		// Get the captured output and close the buffer
		return ob_get_clean();
	}


	public function set_filename($file)
	{
		if (!file_exists($file))
		{
			throw new View_Exception('The requested view :file could not be found', array(
				':file' => $file,
			));
		}

		// Store the file path locally
		$this->_file = $file;

		return $this;
	}

}