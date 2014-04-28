<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_Page_Sitemap {
	
	/**
	 * Хранение карт сайта с разынми параметрами
	 * @var array 
	 */
	protected static $_sitemap = array();
	
	/**
	 * Получение карты сайта
	 * 
	 * @param boolean $include_hidden Включить скрытые страницы
	 * @return Model_Page_Sitemap
	 */
	public static function get( $include_hidden = FALSE)
	{
		$status = ( bool) $include_hidden ? 1 : 0;
		if( ! array_key_exists($status, Model_Page_Sitemap::$_sitemap) )
		{
			$pages = ORM::factory('page')
				->order_by('parent_id', 'asc')
				->order_by('position', 'asc');
			
			if(( bool) $include_hidden === FALSE)
			{
				$pages->where('status_id', 'in', array(Model_Page::STATUS_PASSWORD_PROTECTED, Model_Page::STATUS_PUBLISHED));
			}
			
			$res_pages = $pages->find_all();

			$current_page = Context::instance()->get_page();

			if($current_page instanceof Model_Page_Front)
			{
				$current_page = $current_page->id;
			}

			$_pages = array();
			foreach ($res_pages as $page)
			{
				$_pages[$page->id] = $page->as_array();
				$_pages[$page->id]['uri'] = ''; //'/' . $page->get_uri();
				$_pages[$page->id]['url'] = '';
				$_pages[$page->id]['slug'] = $page->slug;
				$_pages[$page->id]['level'] = 0;
				$_pages[$page->id]['is_active'] = TRUE;//URL::match($_pages[$page->id]['uri']);
			}

			$pages = array();
			foreach ($_pages as & $page)
			{
				$pages[$page['parent_id']][] = & $page;
			}

			foreach ($_pages as & $page)
			{
				if(isset($pages[$page['id']]))
				{
					foreach ($pages[$page['id']] as & $_page)
					{
						$_page['level'] = $page['level'] + 1;
						$_page['parent'] = $page;
						
						$_page['uri'] = $page['uri'] . '/' . $_page['slug'];
						$_page['url'] = URL::frontend($_page['uri']);
						$_page['is_active'] = URL::match($_page['uri']);

						if(empty($_page['layout_file']))
						{
							$_page['layout_file'] = $page['layout_file'];
						}
						
						if($_page['is_active'])
							$page['is_active'] = TRUE;
					}

					$page['childs'] = $pages[$page['id']];
				}
			}

			Model_Page_Sitemap::$_sitemap[$status] = new Sitemap(reset($pages));
		}

		return clone(Model_Page_Sitemap::$_sitemap[$status]);
	}
}