<?php defined('SYSPATH') OR die('No direct script access.');

class Update {
	
	const VERSION_NEW = 1;
	const VERSION_OLD = -1;
	const VERSION_CURRENT = 0;
	
	const BRANCH = 'dev';
	const REPOSITORY = 'butschster/kodicms';
	
	/**
	 * Версия системы с удаленного сервера
	 * @var string 
	 */
	protected static $_remove_version = NULL;


	/**
	 * Проверка номера версии в репозитории Github
	 * @return integer
	 */
	public static function check_version()
	{
		$respoonse = self::request('https://raw.githubusercontent.com/:rep/:branch/cms/application/bootstrap.php');
		preg_match('/define\(\'CMS_VERSION\'\,[\t\ ]*\'([0-9\.]+)\'\)\;/i', $respoonse, $matches);
		
		self::$_remove_version = $matches[1];

		return version_compare(CMS_VERSION, self::$_remove_version);
	}
	
	/**
	 * Проверка файлов на различия, проверяется по размеру файла и наличие файла в ФС
	 * @retun array
	 */
	public static function check_files()
	{
		$respoonse = self::request('https://api.github.com/repos/:rep/git/trees/:branch?recursive=true');
		$respoonse = json_decode($respoonse, TRUE);
		
		$files = array(
			'new_files' => array(),
			'diff_files' => array(),
			'third_party_plugins' => array(),
		);
		
		$cache = Cache::instance();
		$cached_files = $cache->get('update_cache');
		
		if($cached_files !== NULL)
		{
			return $cached_files;
		}

		if(isset($respoonse['tree']))
		{
			$plugins = array();
			
			foreach($respoonse['tree'] as $row)
			{
				$filepath = DOCROOT . $row['path'];
				if ( ! file_exists($filepath))
				{
					$files['new_files'][] = self::build_remote_url('https://raw.githubusercontent.com/:rep/:branch/' . $row['path']);
					continue;
				}
				
				if (is_dir($filepath))
				{
					if( preg_match('/cms\/plugins\/([\w\_]+)/', $filepath, $matches))
					{
						if ( ! empty($matches[1]))
						{
							$plugins[$matches[1]] = $matches[1];
						}
					}

					continue;
				}
				
				$filesize = filesize($filepath);
				if ($filesize != $row['size'] )
				{
					// Linux файлы имеют размер отличный от Windows файлов из за 
					// разного подсчета символов окончания строки LF против CR LF
					if (Kohana::$is_windows)
					{
						$diff = $filesize - self::_count_file_lines($filepath) - $row['size'];
					}
					else
					{
						$diff = $filesize - $row['size'];
					}
					
					if ($diff > 1 OR $diff < - 1)
					{
						$files['diff_files'][] = array(
							'diff' => Text::bytes($diff),
							'url' => self::build_remote_url('https://raw.githubusercontent.com/:rep/:branch/' . $row['path'])
						);
					}
				}
			}
			
			if ( ! empty($plugins))
			{
				$local_plugins = array_keys(Plugins::find_all());
				$files['third_party_plugins'] = array_diff($local_plugins, $plugins);
			}
			
			$cache->set('update_cache', $files);
		}
		
		return $files;
	}
	
	/**
	 * Ссылка на удаленный репозиторий
	 * @param string $name
	 * @return string
	 */
	public static function link($name)
	{
		return HTML::anchor(self::build_remote_url('https://github.com/:rep/archive/:branch.zip'), $name);
	}
	
	/**
	 * Получение номер версии с удаленного сервера
	 * @return string
	 */
	public static function remote_version()
	{
		if(self::$_remove_version === NULL)
		{
			self::check();
		}
		
		return self::$_remove_version;
	}
	
	/**
	 * 
	 * @param string $url
	 * @return string
	 */
	public static function build_remote_url($url)
	{
		return strtr($url, array(
			':branch' => self::BRANCH,
			':rep' => self::REPOSITORY
		));
	}

	/**
	 * 
	 * @param string $url
	 * @return string
	 */
	public static function request($url)
	{
		return Request::factory(self::build_remote_url($url), array(
			'cache' => HTTP_Cache::factory(Cache::instance()),
			'options' => array(
				CURLOPT_SSL_VERIFYPEER => FALSE,
				CURLOPT_SSL_VERIFYHOST => FALSE,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5'
			)
		))->execute()->body();
	}

	/**
	 * Подсчет кол-ва строк в файле
	 * 
	 * @param string $filepath
	 * @return int
	 */
	protected static function _count_file_lines($filepath)
	{
		$handle = fopen( $filepath, "r" );
		$count = 0;
		while( fgets($handle) ) 
		{
			$count++;
		}
		fclose($handle);
		return $count;
	}
}