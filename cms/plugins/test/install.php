<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Build layout blocks
 */
$layouts = Model_File_Layout::find_all();
		
foreach($layouts as $layout)
{
	$layout->rebuild_blocks();
}

/**
 * Install widgets
 */
if(class_exists('Widget_Manager'))
{
	$widgets = array(
		array(
			'type' => 'page_menu',
			'data' => array (
				'name' => 'Header menu',
				'template' => 'header',
				'cache_tags' => 'pages',
				'page_id' => 1,
				'exclude' => array (
					6, 4, 2
				)
			),
			'blocks' => array (
				1 => 'header',
				3 => 'header',
				8 => 'header',
				6 => 'header',
				5 => 'header',
				7 => 'header',
				9 => 'header',
				10 => 'header',
				2 => 'header',
				11 => 'header'
			)
		),
		array(
			'type' => 'page_breadcrumbs',
			'data' => array(
				'name' => 'Breadcrumbs',
				'template' => 'bradcrumbs',
				'cache_tags' => 'pages'
			),
			'blocks' => array (
				1 => 'bradcrumbs',
				3 => 'bradcrumbs',
				8 => 'bradcrumbs',
				6 => 'bradcrumbs',
				5 => 'bradcrumbs',
				7 => 'bradcrumbs',
				9 => 'bradcrumbs',
				10 => 'bradcrumbs',
				2 => 'bradcrumbs',
				11 => 'bradcrumbs'
			)
		),
		array(
			'type' => 'html',
			'data' => array (
				'name' => 'Footer',
				'template' => 'footer'
			),
			'blocks' => array (
				1 => 'footer',
				3 => 'footer',
				8 => 'footer',
				6 => 'footer',
				5 => 'footer',
				7 => 'footer',
				9 => 'footer',
				10 => 'footer',
				2 => 'footer',
				11 => 'footer'
			)
		),
		array(
			'type' => 'html',
			'data' => array (
				'name' => 'Sidebar',
				'template' => 'sidebar'
			),
			'blocks' => array (
				1 => 'sidebar',
				3 => 'sidebar',
				8 => 'sidebar',
				6 => 'sidebar',
				5 => 'sidebar',
				7 => 'sidebar',
				9 => 'sidebar',
				10 => 'sidebar',
				2 => 'sidebar',
				11 => 'sidebar'
			)
		),
		array(
			'type' => 'html',
			'data' => array (
				'name' => 'Top banner',
				'template' => 'top_banner'
			),
			'blocks' => array (
				1 => 'top_banner'
			)
		),
		'articles_headline' => array(
			'type' => 'page_pages',
			'data' => array (
				'name' => 'Articles headline',
				'template' => 'archive-headline',
				'cache_tags' => 'pages,page_parts,page_tags',
			),
			'blocks' => array (
				8 => 'body'
			)
		),
		array(
			'type' => 'page_pages',
			'data' => array (
				'name' => 'Recent entries',
				'template' => 'recent-entries',
				'caching' => 1,
				'cache_lifetime' => 3600,
				'cache_tags' => 'pages,page_parts,page_tags',
				'header' => 'Recent entries',
				'page_id' => 8,
			),
			'blocks' => array (
				3 => 'recent',
				8 => 'recent',
				6 => 'recent',
				5 => 'recent',
				7 => 'recent',
				9 => 'recent',
				10 => 'recent',
				2 => 'recent'
			)
		),
		array(
			'type' => 'page_pages',
			'data' => array (
				'name' => 'Recent entries index page',
				'template' => 'recent-entries',
				'caching' => 1,
				'cache_lifetime' => 3600,
				'cache_tags' => 'pages,page_parts,page_tags',
				'page_id' => 8,
			),
			'blocks' => array (
				1 => 'extended'
			)
		),
		array(
			'type' => 'page_pages',
			'data' => array (
				'name' => 'last entry index page',
				'template' => 'last-entry',
				'caching' => 1,
				'cache_lifetime' => 3600,
				'cache_tags' => 'pages,page_parts,page_tags',
				'page_id' => 8,
				'list_size' => 1
			),
			'blocks' => array (
				1 => 'body'
			)
		),
		array(
			'type' => 'page_pages',
			'data' => array (
				'name' => 'Recent entries RSS',
				'template' => 'recent-entries-rss',
				'caching' => 1,
				'cache_lifetime' => 3600,
				'cache_tags' => 'pages,page_parts,page_tags',
				'page_id' => 8,
			),
			'blocks' => array (
				4 => 'body'
			)
		),
		array(
			'type' => 'sendmail',
			'data' => array (
				'name' => ' Send mail (sender)',
				'template' => 'send-mail-template',
				'allowed_tags' => '<b><i><u><p>',
				'field' => array (
					'source' => array (
						0 => '0',
						1 => '2',
						2 => '2',
						3 => '2',
					),
					'id' => array (
						0 => '',
						1 => 'subject',
						2 => 'email',
						3 => 'text',
					),
					'type' => array (
						0 => '10',
						1 => '10',
						2 => '10',
						3 => '20',
					),
					'validator' => array (
						0 => '',
						1 => 'not_empty',
						2 => 'email',
						3 => '',
					),
					'error' => array (
						0 => '',
						1 => '',
						2 => '',
						3 => '',
					),
				),
			),
			'blocks' => array (
				12 => 'body'
			)
		),
		array(
			'type' => 'html',
			'data' => array (
				'name' => 'Send mail (form)',
				'template' => 'send-mail-form'
			),
			'blocks' => array (
				11 => 'body'
			),
		),
		array(
			'type' => 'pagination',
			'data' => array (
				'name' => 'Постраничная навигация',
				'template' => 'paginator',
				'related_widget_id' => 8,
				'query_key' => 'page',
				'related_widget_id' => NULL
			),
			'blocks' => array (
				8 => 'pagination'
			),
		),
	);

	$installed_widgets = array();

	foreach ($widgets as $key => $widget)
	{
		if(isset($widget['data']['related_widget_id']) AND isset($installed_widgets[$widget['data']['related_widget_id']]))
		{
			$widget['data']['related_widget_id'] = $installed_widgets[$widget['data']['related_widget_id']];
		}

		$installed_widgets[$key] = Widget_Manager::install($widget);
	}
}