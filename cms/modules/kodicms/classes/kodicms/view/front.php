<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS
 * @author		ButscHSter
 */
class KodiCMS_View_Front extends View {
	
	/**
	 * 
	 * @param string $file
	 * @param array $data
	 * @return \View_Front
	 */
	public static function factory($file = NULL, array $data = NULL)
	{
		return new View_Front($file, $data);
	}
	
	/**
	 * 
	 * @param string $html
	 * @return string
	 */
	public function render_html($html)
	{
		// Combine local and global data and capture the output
		return View_Front::capture_html($html, $this->_data);
	}
	
	/**
	 * 
	 * @param string $html
	 * @param array $view_data
	 * @return string
	 * @throws Exception
	 */
	protected static function capture_html($html, array $view_data)
	{
		// Import the view variables to local namespace
		extract($view_data, EXTR_SKIP);
		
		if (View::$_global_data)
		{
			// Import the global view variables to local namespace
			extract(View::$_global_data, EXTR_SKIP | EXTR_REFS);
		}

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

	/**
	 * 
	 * @param string $file
	 * @return \KodiCMS_View_Front
	 * @throws View_Exception
	 */
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