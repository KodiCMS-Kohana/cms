<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Allows assets (CSS, Javascript, etc.) to be included throughout the application, and then outputted later based on dependencies.
 * This makes sure all assets will be included in the correct order, no matter what order they are defined in.
 *
 *     // Call this anywhere in your application, most likely in a template controller
 *     Assets::css('global', 'assets/css/global.css', array('grid', 'reset'), array('media' => 'screen'));
 *     Assets::css('reset', 'assets/css/reset.css');
 *     Assets::css('grid', 'assets/css/grid.css', 'reset');
 *     
 *     Assets::js('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
 *     Assets::js('global', 'assets/js/global.js', array('jquery'));
 *     Assets::js('stats', 'assets/js/stats.js', NULL, TRUE);
 *     
 *     Assets::group('head', 'keywords', '<meta name="keywords" content="one,two,three,four,five" />');
 *     Assets::group('head', 'description', '<meta name="description" content="Description of webpage here" />');
 *     
 *     // In your view file
 *     <html>
 *         <head>
 *             <title>Kohana Assets</title>
 *             <?php echo Assets::css() ?>
 *             <?php echo Assets::js() ?>
 *             <?php echo Assets::group('head') ?>
 *         </head>
 *         <body>
 *             <!-- Content -->
 *             <?php echo Assets::js(TRUE) ?>
 *         </body>
 *     </html>
 *
 * @package		KodiCMS/Assets
 * @author		Corey Worrell
 * @author		butschster <butschster@gmail.com>
 * @version		1.0
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Assets_Core {

	/**
	 * @var  array  CSS assets
	 */
	public static $css = array();
	
	/**
	 *
	 * @var array  Javascript assets to minify 
	 */
	protected static $_css_minify = array();
	
	
	/**
	 * @var  array  Javascript assets
	 */
	public static $js = array();
	
	/**
	 *
	 * @var array  Javascript assets to minify 
	 */
	protected static $_js_minify = array();
	
	/**
	 * @var  array  Other asset groups (meta data, links, etc...)
	 */
	public static $groups = array();
	
	/**
	 * 
	 * @param string|array $names
	 * @param boolean $footer
	 * @return boolean
	 */
	public static function package($names, $footer = FALSE)
	{
		if (!is_array($names))
		{
			$names = array($names);
		}

		foreach ($names as $name)
		{
			$package = Assets_Package::load($name);

			if ($package === NULL)
				continue;

			foreach ($package as $item)
			{
				switch ($item['type'])
				{
					case 'css':
						Assets::$css[$item['handle']] = $item;
						break;
					case 'js':
						$item['footer'] = (bool) $footer;
						Assets::$js[$item['handle']] = $item;
						break;
				}
			}
		}

		return TRUE;
	}
	
	/**
	 * 
	 * @param string $path
	 * @param string $ext
	 * @param string $cache_key
	 * @param integer $lifetime
	 * @return string
	 */
	public static function merge_files($path, $ext, $cache_key = NULL, $lifetime = Date::DAY)
	{
		$cache = Cache::instance();

		if ($cache_key === NULL)
		{
			$cache_key = 'assets::merge::' . URL::title($path, '::') . '::' . $ext;
		}

		$content = $cache->get($cache_key);

		if ($content === NULL)
		{
			$files = Kohana::find_file('media', FileSystem::normalize_path($path), $ext, TRUE);
			if (!empty($files))
			{
				foreach ($files as $file)
				{
					$content .= file_get_contents($file) . "\n";
				}

				if (Kohana::$caching === TRUE)
				{
					$cache->set($cache_key, $content, $lifetime);
				}
			}
		}

		return $content;
	}

	/**
	 * CSS wrapper
	 *
	 * Gets or sets CSS assets
	 *
	 * @param   string   Asset name.
	 * @param   string   Asset source
	 * @param   mixed    Dependencies
	 * @param   array    Attributes for the <link /> element
	 * @return  mixed    Setting returns asset array, getting returns asset HTML
	 */
	public static function css($handle = NULL, $src = NULL, $deps = NULL, $attrs = NULL)
	{
		// Return all CSS assets, sorted by dependencies
		if ($handle === NULL)
		{
			return Assets::all_css();
		}

		// Return individual asset
		if ($src === NULL)
		{
			return Assets::get_css($handle);
		}

		// Set default media attribute
		if (!isset($attrs['media']))
		{
			$attrs['media'] = 'all';
		}

		return Assets::$css[$handle] = array(
			'src'   => $src,
			'deps'  => (array) $deps,
			'attrs' => $attrs,
			'handle' => $handle,
			'type' => 'css'
		);
	}
	
	/**
	 * Get a single CSS asset
	 *
	 * @param   string   Asset name
	 * @return  string   Asset HTML
	 */
	public static function get_css($handle)
	{
		if (!isset(Assets::$css[$handle]))
		{
			return FALSE;
		}

		$asset = Assets::$css[$handle];

		if (in_array($asset['src'], Assets::$_css_minify))
		{
			return NULL;
		}

		return HTML::style($asset['src'], $asset['attrs']);
	}
	
	/**
	 * Get all CSS assets, sorted by dependencies
	 *
	 * @return   string   Asset HTML
	 */
	public static function all_css()
	{
		if (empty(Assets::$css))
		{
			return FALSE;
		}
		
		foreach (Assets::_sort(Assets::$css) as $handle => $data)
		{
			$assets[] = Assets::get_css($handle);
		}
		
		return implode("", $assets);
	}
	
	/**
	 * Remove a CSS asset, or all
	 *
	 * @param   mixed   Asset name, or `NULL` to remove all
	 * @return  mixed   Empty array or void
	 */
	public static function remove_css($handle = NULL)
	{
		if ($handle === NULL)
		{
			return Assets::$css = array();
		}
		
		unset(Assets::$css[$handle]);
	}
	
	/**
	 * Javascript wrapper
	 *
	 * Gets or sets javascript assets
	 *
	 * @param   mixed    Asset name if `string`, sets `$footer` if boolean
	 * @param   string   Asset source
	 * @param   mixed    Dependencies
	 * @param   bool     Whether to show in header or footer
	 * @return  mixed    Setting returns asset array, getting returns asset HTML
	 */
	public static function js($handle = FALSE, $src = NULL, $deps = NULL, $footer = FALSE)
	{
		if ($handle === TRUE OR $handle === FALSE)
		{
			return Assets::all_js($handle);
		}
		
		if ($src === NULL)
		{
			return Assets::get_js($handle);
		}
		
		return Assets::$js[$handle] = array(
			'src'    => $src,
			'deps'   => (array) $deps,
			'footer' => (bool) $footer,
			'handle' => $handle,
			'type' => 'js'
		);
	}
	
	/**
	 * Get a single javascript asset
	 *
	 * @param   string   Asset name
	 * @return  string   Asset HTML
	 */
	public static function get_js($handle)
	{
		if (!isset(Assets::$js[$handle]))
		{
			return FALSE;
		}

		$asset = Assets::$js[$handle];

		if (in_array($asset['src'], Assets::$_js_minify))
		{
			return NULL;
		}

		return HTML::script($asset['src']);
	}
	
	/**
	 * Get all javascript assets of section (header or footer)
	 *
	 * @param   bool   FALSE for head, TRUE for footer
	 * @return  string Asset HTML
	 */
	public static function all_js($footer = FALSE)
	{
		if (empty(Assets::$js))
		{
			return FALSE;
		}

		$assets = array();

		foreach (Assets::$js as $handle => $data)
		{
			if ($data['footer'] === $footer)
			{
				$assets[$handle] = $data;
			}
		}

		if (empty($assets))
		{
			return FALSE;
		}

		foreach (Assets::_sort($assets) as $handle => $data)
		{
			$sorted[] = Assets::get_js($handle);
		}

		return implode("", $sorted);
	}
	
	/**
	 * Remove a javascript asset, or all
	 *
	 * @param   mixed   Remove all if `NULL`, section if `TRUE` or `FALSE`, asset if `string`
	 * @return  mixed   Empty array or void
	 */
	public static function remove_js($handle = NULL)
	{
		if ($handle === NULL)
		{
			return Assets::$js = array();
		}

		if ($handle === TRUE OR $handle === FALSE)
		{
			foreach (Assets::$js as $handle => $data)
			{
				if ($data['footer'] === $handle)
				{
					unset(Assets::$js[$handle]);
				}
			}

			return;
		}

		unset(Assets::$js[$handle]);
	}
	
	/**
	 * Group wrapper
	 *
	 * @param   string   Group name
	 * @param   string   Asset name
	 * @param   string   Asset content
	 * @param   mixed    Dependencies
	 * @return  mixed    Setting returns asset array, getting returns asset content
	 */
	public static function group($group, $handle = NULL, $content = NULL, $deps = NULL)
	{
		if ($handle === NULL)
		{
			return Assets::all_group($group);
		}

		if ($content === NULL)
		{
			return Assets::get_group($group, $handle);
		}

		return Assets::$groups[$group][$handle] = array(
			'content' => $content,
			'deps' => (array) $deps,
		);
	}
	
	/**
	 * Get a single group asset
	 *
	 * @param   string   Group name
	 * @param   string   Asset name
	 * @return  string   Asset content
	 */
	public static function get_group($group, $handle)
	{
		if (!isset(Assets::$groups[$group]) OR ! isset(Assets::$groups[$group][$handle]))
		{
			return FALSE;
		}

		return Assets::$groups[$group][$handle]['content'];
	}
	
	/**
	 * Get all of a groups assets, sorted by dependencies
	 *
	 * @param  string   Group name
	 * @return string   Assets content
	 */
	public static function all_group($group)
	{
		if (!isset(Assets::$groups[$group]))
		{
			return FALSE;
		}

		foreach (Assets::_sort(Assets::$groups[$group]) as $handle => $data)
		{
			$assets[] = Assets::get_group($group, $handle);
		}

		return implode("", $assets);
	}
	
	/**
	 * Remove a group asset, all of a groups assets, or all group assets
	 *
	 * @param   string   Group name
	 * @param   string   Asset name
	 * @return  mixed    Empty array or void
	 */
	public static function remove_group($group = NULL, $handle = NULL)
	{
		if ($group === NULL)
		{
			return Assets::$groups = array();
		}

		if ($handle === NULL)
		{
			unset(Assets::$groups[$group]);
			return;
		}

		unset(Assets::$groups[$group][$handle]);
	}
	
	/**
	 * 
	 * @param string $cache_dir_path
	 * @return array
	 */
	public static function minify($cache_dir_path = NULL)
	{
		Assets::$_js_minify = Assets::$_css_minify = array();

		foreach (Assets::_sort(Assets::$js) as $handle => $js)
		{
			Assets::$_js_minify[] = $js['src'];
		}
		
		foreach (Assets::_sort(Assets::$css) as $handle => $css)
		{
			Assets::$_css_minify[] = $css['src'];
		}

		return array(
			self::_minify(Assets::$_css_minify, 'css', $cache_dir_path),
			self::_minify(Assets::$_js_minify, 'js', $cache_dir_path)
		);
	}

	/**
	 * 
	 * @param array $array
	 * @param string $ext
	 * @param string $cache_dir_path
	 * @return string
	 */
	protected static function _minify($array, $ext, $cache_dir_path = NULL)
	{
		$files = '';
		$url = NULL;

		foreach ($array as $src)
		{
			$files .= $src;
		}

		$filename = md5($files) . '.' . $ext;

		if ($cache_dir_path === NULL)
		{
			$url = PUBLIC_URL . 'cache/';
			$cache_dir_path = PUBLICPATH . 'cache' . DIRECTORY_SEPARATOR;
		}
		
		if (!is_writable($cache_dir_path))
		{
			throw new Kohana_Exception('Unable to write to the cache directory :resource', array(
				':resource' => $cache_dir_path));
		}

		$file_path = $cache_dir_path . $filename;

		if (file_exists($file_path))
		{
			return $url . $filename;
		}

		$minified = array();
		foreach ($array as $src)
		{
			$minified[] = file_get_contents($src);
		}

		switch ($ext)
		{
			case 'css':
				$minified = implode("\n", $minified);
				$minified = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minified);
				$minified = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minified);
				break;
			case 'js':
				$minified = implode(";\n\n\n", $minified);
				$minified = Assets::_compress_script($minified);
				break;
		}

		$file = file_put_contents($file_path, $minified, LOCK_EX);
		return $url . $filename;
	}
	
	/**
	 * 
	 * @param string $script
	 * @return string
	 */
	protected static function _compress_script($script)
	{
		return Assets_Min_JavaScript::minify($script);
	}

	/**
	 * Sorts assets based on dependencies
	 *
	 * @param   array   Array of assets
	 * @return  array   Sorted array of assets
	 */
	protected static function _sort($assets)
	{
		$original = $assets;
		$sorted = array();

		while (count($assets) > 0)
		{
			foreach ($assets as $key => $value)
			{
				// No dependencies anymore, add it to sorted
				if (empty($assets[$key]['deps']))
				{
					$sorted[$key] = $value;
					unset($assets[$key]);
				}
				else
				{
					foreach ($assets[$key]['deps'] as $k => $v)
					{
						// Remove dependency if doesn't exist, if its dependent on itself, or if the dependent is dependent on it
						if (!isset($original[$v]) OR $v === $key OR ( isset($assets[$v]) AND in_array($key, $assets[$v]['deps'])))
						{
							unset($assets[$key]['deps'][$k]);
							continue;
						}

						// This dependency hasn't been sorted yet
						if (!isset($sorted[$v]))
							continue;

						// This dependency is taken care of, remove from list
						unset($assets[$key]['deps'][$k]);
					}
				}
			}
		}

		return $sorted;
	}
	
	/**
	 * Enforce static usage
	 */	
	private function __contruct() {}
	private function __clone() {}

}