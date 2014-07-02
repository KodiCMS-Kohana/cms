<?php defined('SYSPATH') OR die('No direct script access.');

class Update {
	
	const VERSION_NEW = 1;
	const VERSION_OLD = -1;
	const VERSION_CURRENT = 0;
	
	const BRANCH = 'dev';
	
	/**
	 * Версия системы с удаленного сервера
	 * @var string 
	 */
	protected static $_remove_version = NULL;


	/**
	 * Проверка номера версии в репозитории Github
	 * @return integer
	 */
	public static function check()
	{
		$respoonse = self::_request('https://raw.githubusercontent.com/butschster/kodicms/:branch/cms/application/bootstrap.php');
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
		$respoonse = self::_request('https://api.github.com/repos/butschster/kodicms/git/trees/:branch?recursive=true');
		$respoonse = json_decode($respoonse, TRUE);
		
		$new_files = array();
		$wrong_files = array();

		if(isset($respoonse['tree']))
		{
			foreach($respoonse['tree'] as $row)
			{
				$filepath = DOCROOT . $row['path'];
				if ( ! file_exists($filepath))
				{
					$new_files[] = 'https://raw.githubusercontent.com/butschster/kodicms/' . self::BRANCH . '/' . $row['path'];
					continue;
				}
				
				if ( is_dir($filepath)) continue;
				
				$filesize = filesize($filepath);
				if($filesize != $row['size'] )
				{
					// Linux файлы имеют размер отличный от Windows файлов из за 
					// разного подсчета символов окончания строки LF против CR LF
					if(Kohana::$is_windows)
					{
						$diff = $filesize - self::_count_file_lines($filepath) - $row['size'];
					}
					else
					{
						$diff = $filesize - $row['size'];
					}
					
					if($diff > 1 OR $diff < - 1)
					{
						$wrong_files[] = array(
							'diff' => $diff,
							'url' => 'https://raw.githubusercontent.com/butschster/kodicms/' . self::BRANCH . '/' . $row['path']
						);
					}
				}
			}
		}
		
		return array(
			'new_files' => $new_files,
			'diff_files' => $wrong_files
		);
	}
	
	/**
	 * Ссылка на удаленный репозиторий
	 * @param string $name
	 * @return string
	 */
	public static function link($name)
	{
		return HTML::anchor('https://github.com/butschster/kodicms/archive/' . self::BRANCH . '.zip', $name);
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
	protected static function _request($url)
	{
		$request = Request::factory(strtr($url, array(
			':branch' => self::BRANCH
		)), array(
			'options' => array(
				CURLOPT_SSL_VERIFYPEER => FALSE,
				CURLOPT_SSL_VERIFYHOST => FALSE,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5'
			)
		))->execute();
		
		return $request->body();
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