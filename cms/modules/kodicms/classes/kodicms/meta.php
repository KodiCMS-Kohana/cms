<?php defined('SYSPATH') or die('No direct access allowed.');

class KodiCMS_Meta {
	
	/**
	 * 
	 * @param Model_Page_Front $page
	 * @return \self
	 */
	public static function factory( Model_Page_Front $page )
	{
		return new self($page);
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
			->group('title', '<title>' . $this->_page->meta_title() . '</title>')
			->group('keywords', '<meta name="keywords" content="' . $this->_page->meta_keywords() . '" />')
			->group('description', '<meta name="description" content="' . $this->_page->meta_description() . '" />')
			->group('content-type', '<meta http-equiv="content-type" content="' . $this->_page->mime() . '; charset=utf-8" />')
			->group('robots', '<meta name="robots" content="' . $this->_page->robots . '" />');
	}
	
	/**
	 * 
	 * @param string $title
	 * @return KodiCMS_Meta
	 */
	public function title( $title )
	{
		return $this->group('title', '<title>' . $title . '</title>');
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
	 * Group wrapper
	 *
	 * @param   string   Group name
	 * @param   string   Asset name
	 * @param   string   Asset content
	 * @param   mixed    Dependencies
	 * @return  KodiCMS_Meta
	 */
	public function group($handle = NULL, $content = NULL, $deps = NULL)
	{
		Assets::group('head', $handle, $content, $deps);
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