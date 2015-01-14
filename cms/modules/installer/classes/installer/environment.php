<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * @package		KodiCMS/Installer
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Installer_Environment {
	
	/**
	 * 
	 * @return array
	 */
	public static function check()
	{
		if (version_compare(PHP_VERSION, '5.3', '<'))
		{
			// Clear out the cache to prevent errors. This typically happens on Windows/FastCGI.
			clearstatcache();
		}
		else
		{
			// Clearing the realpath() cache is only possible PHP 5.3+
			clearstatcache(TRUE);
		}

		$env = new Installer_Environment;
		$methods = get_class_methods($env);

		$result = array(0 => FALSE, 1 => array(), 2 => array());
		$failed = FALSE;

		foreach (get_class_methods($env) as $method)
		{
			if (strpos($method, 'test') === FALSE)
			{
				continue;
			}

			$return = call_user_func(array($env, $method));
			if (empty($return))
			{
				continue;
			}

			$key = 1;
			if (strpos($method, 'optional') !== FALSE)
			{
				$key = 2;
			}

			$status = $return['condition'];

			$data = array(
				'title' => $return['title'],
				'failed' => !$status,
				'notice' => Arr::get($return, 'notice')
			);

			if ($status)
			{
				$data['message'] = Arr::get($return, 'success', __('Pass'));
			}
			else
			{
				$data['message'] = Arr::get($return, 'error', __('Failed'));
				
				if ($key === 1)
				{
					$failed = TRUE;
				}
			}

			$result[$key][substr($method, 5)] = $data;
		}

		$result[0] = $failed;

		return $result;
	}
	
	public function test_php()
	{
		return array(
			'title' => __('PHP Version'),
			'condition' => version_compare(PHP_VERSION, '5.3.3', '>='),
			'error' => __('Kohana requires PHP 5.3.3 or newer, this version is :version.', array(':version' => PHP_VERSION)),
			'success' => PHP_VERSION
		);
	}
	
	public function test_sys_path()
	{
		return array(
			'title' => __('System Directory'),
			'condition' => (is_dir(SYSPATH) AND is_file(SYSPATH . 'classes/kohana' . EXT)),
			'error' => __('The configured :dir directory does not exist or does not contain required files.', array(
				':dir' => '<code>system</code>'
			)),
			'success' => SYSPATH
		);
	}

	public function test_app_path()
	{
		return array(
			'title' => __('Application Directory'),
			'condition' => (is_dir(APPPATH) AND is_file(APPPATH . 'bootstrap' . EXT)),
			'error' => __('The configured :dir directory does not exist or does not contain required files.', array(
				':dir' => '<code>application</code>'
			)),
			'success' => APPPATH
		);
	}

	public function test_cache_path()
	{
		return array(
			'title' => __('Cache Directory'),
			'condition' => (is_dir(CMSPATH) AND is_dir(CMSPATH . 'cache') AND is_writable(CMSPATH . 'cache')),
			'error' => __('The :dir directory is not writable.', array(
				':dir' => '<code>' . CMSPATH . 'cache/</code>'
			)),
			'success' => CMSPATH . 'cache/'
		);
	}

	public function test_logs_path()
	{
		return array(
			'title' => __('Logs Directory'),
			'condition' => (is_dir(CMSPATH) AND is_dir(CMSPATH . 'logs') AND is_writable(CMSPATH . 'logs')),
			'error' => __('The :dir directory is not writable.', array(
				':dir' => '<code>' . CMSPATH . 'logs/</code>'
			)),
			'success' => CMSPATH . 'logs/'
		);
	}

	public function test_config_placement()
	{
		$condition = (is_dir(pathinfo(CFGFATH, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR) AND ! is_file(CFGFATH) AND is_writable(pathinfo(CFGFATH, PATHINFO_DIRNAME)));

		$error = NULL;
		if (!$condition)
		{
			if (!is_writable(pathinfo(CFGFATH, PATHINFO_DIRNAME)))
			{
				$error = __('The config :dir directory must be writable or empty config file :file created with write access', array(
					':dir' => pathinfo(CFGFATH, PATHINFO_DIRNAME),
					':file' => pathinfo(CFGFATH, PATHINFO_FILENAME)
				));
			}
			else
			{
				$error = __('The config :dir directory does not exist.', array(
					':dir' => CFGFATH, ':file' => pathinfo(CFGFATH, PATHINFO_FILENAME) . '.' . pathinfo(CFGFATH, PATHINFO_EXTENSION)
				));
			}
		}
		return array(
			'title' => __('Config file placement'),
			'condition' => $condition,
			'error' => $error,
			'success' => CFGFATH,
			'notice' => array(
				'class' => 'alert alert-warning',
				'message' => __('To change config file placement edit index.php file')
			)
		);
	}

	public function test_pcre_utf8()
	{
		$condition = TRUE;

		$error = NULL;
		if (!@preg_match('/^.$/u', 'ñ'))
		{
			$condition = FALSE;
			$error = '<a href="http://php.net/pcre" target="blank">PCRE</a> has not been compiled with UTF-8 support.';
		}
		else if (!@preg_match('/^\pL$/u', 'ñ'))
		{
			$condition = FALSE;
			$error = '<a href="http://php.net/pcre" target="blank">PCRE</a> has not been compiled with Unicode property support.';
		}

		return array(
			'title' => 'PCRE UTF-8',
			'condition' => $condition,
			'error' => $error
		);
	}

	public function test_spl()
	{
		return array(
			'title' => 'SPL Enabled',
			'condition' => function_exists('spl_autoload_register'),
			'error' => 'PHP <a href="http://www.php.net/spl" target="blank">SPL</a> is either not loaded or not compiled in.'
		);
	}

	public function test_reflection()
	{
		return array(
			'title' => 'Reflection Enabled',
			'condition' => class_exists('ReflectionClass'),
			'error' => 'PHP <a href="http://www.php.net/reflection" target="blank">reflection</a> is either not loaded or not compiled in.'
		);
	}

	public function test_filters()
	{
		return array(
			'title' => 'Filters Enabled',
			'condition' => function_exists('filter_list'),
			'error' => 'The <a href="http://www.php.net/filter" target="blank">filter</a> extension is either not loaded or not compiled in.'
		);
	}

	public function test_iconv()
	{
		return array(
			'title' => 'Iconv Extension Loaded',
			'condition' => extension_loaded('iconv'),
			'error' => 'The <a href="http://php.net/iconv" target="blank">iconv</a> extension is not loaded.'
		);
	}

	public function test_hash()
	{
		return array(
			'title' => 'Hash Extension Loaded',
			'condition' => (extension_loaded('hash')),
			'error' => 'The <a href="http://php.net/hash" target="blank">hash</a> extension is not loaded.'
		);
	}

	public function test_mbstring()
	{
		if (extension_loaded('mbstring'))
		{
			return array(
				'title' => 'Mbstring Not Overloaded',
				'condition' => !(ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING),
				'error' => 'The <a href="http://php.net/mbstring" target="blank">mbstring</a> extension is overloading PHP\'s native string functions.'
			);
		}

		return FALSE;
	}

	public function test_ctype()
	{
		return array(
			'title' => 'Character Type (CTYPE) Extension',
			'condition' => function_exists('ctype_digit'),
			'error' => 'The <a href="http://php.net/ctype" target="blank">ctype</a> extension is not enabled.'
		);
	}

	public function test_uri_etermination()
	{
		return array(
			'title' => 'URI Determination',
			'condition' => (isset($_SERVER['REQUEST_URI']) OR isset($_SERVER['PHP_SELF']) OR isset($_SERVER['PATH_INFO'])),
			'error' => 'Neither <code>$_SERVER[\'REQUEST_URI\']</code>, <code>$_SERVER[\'PHP_SELF\']</code>, or <code>$_SERVER[\'PATH_INFO\']</code> is available.'
		);
	}

	public function optional_test_pecl_http()
	{
		return array(
			'title' => 'PECL HTTP Enabled',
			'condition' => extension_loaded('http'),
			'error' => __('Kohana can use the :extension extension for the :class class.', array(
				':extension' => '<a href="http://php.net/http" target="blank">http</a>',
				':class' => 'Request_Client_External'
			))
		);
	}

	public function optional_test_curl()
	{
		return array(
			'title' => 'cURL Enabled',
			'condition' => extension_loaded('curl'),
			'error' => __('Kohana can use the :extension extension for the :class class.', array(
				':extension' => '<a href="http://php.net/curl" target="blank">cURL</a>',
				':class' => 'Request_Client_External'
			))
		);
	}

	public function optional_test_mcrypt()
	{
		return array(
			'title' => 'mcrypt Enabled',
			'condition' => extension_loaded('mcrypt'),
			'error' => __('Kohana requires :extension for the :class class.', array(
				':extension' => '<a href="http://php.net/mcrypt" target="blank">mcrypt</a>',
				':class' => 'Encrypt'
			))
		);
	}

	public function optional_test_gd()
	{
		return array(
			'title' => 'GD Enabled',
			'condition' => function_exists('gd_info'),
			'error' => __('Kohana requires :extension for the :class class.', array(
				':extension' => '<a href="http://php.net/gd" target="blank">GD</a>',
				':class' => 'Image'
			))
		);
	}
	
	public function optional_test_mysql()
	{
		if(! version_compare(PHP_VERSION, '5.5.0', '>='))
		{
			return array(
				'title' => 'MySQL Enabled',
				'condition' => function_exists('mysql_connect'),
				'error' => __('Kohana can use the :extension extension for the :class class.', array(
					':extension' => '<a href="http://php.net/mysql" target="blank">MySQL</a>',
					':class' => 'MySQL'
				))
			);
		}
		
		return FALSE;
	}
	
	public function optional_test_mysqli()
	{
		return array(
			'title' => 'MySQLi Enabled',
			'condition' => function_exists('mysqli_connect'),
			'error' => __('Kohana can use the :extension extension for the :class class.', array(
				':extension' => '<a href="http://php.net/mysqli" target="blank">MySQLi</a>',
				':class' => 'MySQLi'
			))
		);
	}
	
	public function optional_test_pdo()
	{
		return array(
			'title' => 'PDO Enabled',
			'condition' => class_exists('PDO'),
			'error' => __('Kohana can use the :extension to support additional databases.', array(
				':extension' => '<a href="http://php.net/pdo" target="blank">PDO</a>',
				':class' => 'PDO'
			))
		);
	}
	
	public function optional_test_pdo_sqlite3()
	{
		return array(
			'title' => 'SQLite3 Enabled',
			'condition' => extension_loaded('sqlite3'),
			'error' => __('Kohana can use the :extension to support additional databases.', array(
				':extension' => '<a href="http://php.net/sqlite3" target="blank">SQLite3</a>',
				':class' => 'SQLite3'
			))
		);
	}
}