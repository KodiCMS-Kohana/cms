<?php defined('SYSPATH') or die('No direct access allowed.');

class Request extends Kohana_Request {
	
	/**
	 * 
	 * @var boolean 
	 */
	protected static $is_mobile = NULL;

	/**
	 * 
	 * @return boolean
	 */
	public static function is_mobile()
	{
		if(self::$is_mobile !== NULL)
		{
			return self::$is_mobile;
		}

		if(Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Kohana', 'detect mobile');
		}

		$agent = strtolower( Request::$user_agent );
		$mobile_browser = 0;

		if ( preg_match( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent ) )
		{
			$mobile_browser++;
		}

		if ( (isset( $_SERVER['HTTP_ACCEPT'] )) AND (strpos( strtolower( $_SERVER['HTTP_ACCEPT'] ), 'application/vnd.wap.xhtml+xml' ) !== false) )
		{
			$mobile_browser++;
		}

		if ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) )
		{
			$mobile_browser++;
		}

		if ( isset( $_SERVER['HTTP_PROFILE'] ) )
		{
			$mobile_browser++;
		}

		$mobile_ua = substr( $agent, 0, 4 );
		$mobile_agents = array(
			'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
			'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
			'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
			'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
			'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
			'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
			'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
			'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
			'wapr', 'webc', 'winw', 'xda', 'xda-'
		);

		if ( in_array( $mobile_ua, $mobile_agents ) )
		{
			$mobile_browser++;
		}

		if ( isset( $_SERVER['ALL_HTTP'] ) AND strpos( strtolower( $_SERVER['ALL_HTTP'] ), 'operamini' ) !== FALSE )
		{
			$mobile_browser++;
		}

		// Pre-final check to reset everything if the user is on Windows
		if ( strpos( $agent, 'windows' ) !== FALSE )
		{
			$mobile_browser = 0;
		}

		// But WP7 is also Windows, with a slightly different characteristic
		if ( strpos( $agent, 'windows phone' ) !== FALSE )
		{
			$mobile_browser++;
		}
		
		if(isset($benchmark))
		{
			Profiler::stop($benchmark);
		}

		if ( $mobile_browser > 0 )
		{
			self::$is_mobile = TRUE;
		}
		
		self::$is_mobile = FALSE;
		
		return self::$is_mobile;
	}

	/**
	 * 
	 * @return string
	 */
	public static function detect_uri()
	{
		$uri = parent::detect_uri();

		if( ! defined( 'URL_SUFFIX' ))
		{
			return $uri;
		}
		else
		{
			return str_replace(URL_SUFFIX, '', $uri);
		}
	}

	/**
	 * 
	 * @return boolean
	 */
	public function is_iframe()
	{
		return $this->query('type') == 'iframe';
	}
}