<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class KodiCMS_Meta {
	
	/**
	 * 
	 * @param Model_Page_Front $page
	 * @return \self
	 */
	public static function factory( Model_Page_Front $page )
	{
		return new Meta($page);
	}
	
	/**
	 * Remove all assets data
	 * @return \KodiCMS_Meta
	 */
	public static function clear()
	{
		Assets::remove_css();
		Assets::remove_js();
		Assets::remove_group();
	}
	
	/**
	 *
	 * @var Model_Page_Front 
	 */
	protected $_page = NULL;

	/**
	 * 
	 * @param Model_Page_Front $page
	 */
	public function __construct( Model_Page_Front $page )
	{
		$this->_page = $page;

		$this
			->group('title', '<title>:title</title>', array(':title'
				=> HTML::chars($this->_page->meta_title())))
			->group('keywords', '<meta name="keywords" content=":content" />', array(':content' 
				=> HTML::chars($this->_page->meta_keywords())))
			->group('description', '<meta name="description" content=":content" />', array(':content' 
				=> HTML::chars($this->_page->meta_description())))
			->group('content-type', '<meta http-equiv="content-type" content=":content; charset=utf-8" />', array(':content' 
				=> HTML::chars($this->_page->mime())))
			->group('robots', '<meta name="robots" content=":content" />', array(':content' 
				=> HTML::chars($this->_page->robots)));
	}
	
	/**
	 * 
	 * @param string $title
	 * @return KodiCMS_Meta
	 */
	public function title( $title )
	{
		return $this->group('title', '<title>:title</title>', array(':title'
				=> HTML::chars($title)));
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
	 * @return  KodiCMS_Meta
	 */
	public function css($handle = NULL, $src = NULL, $deps = NULL, $attrs = NULL)
	{
		Assets::css($handle, $src, $deps, $attrs);
		return $this;
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
	 * @return  KodiCMS_Meta
	 */
	public function js($handle = FALSE, $src = NULL, $deps = NULL, $footer = FALSE)
	{
		Assets::js($handle, $src, $deps, $footer);
		return $this;
	}
	
	/**
	 * 
	 * @param string $handle
	 * @param string $content
	 * @param array $params
	 * @param string $deps
	 * @return \KodiCMS_Meta
	 */
	public function group($handle = NULL, $content = NULL, $params = array(), $deps = NULL)
	{
		Assets::group('head', $handle, strtr($content, $params), $deps);
		return $this;
	}

	/**
	 * 
	 * @return string
	 */
	public function render()
	{
		return Assets::group('head')
				. Assets::css()
				. Assets::js();
	}
	
	public function __toString()
	{
		return (string) $this->render();
	}
}