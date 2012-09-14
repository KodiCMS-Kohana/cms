<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'disqus',
	'title' => 'Disqus',
	'description' => 'Disqus is a global comment system that improves discussion on websites and connects conversations across the web.',
	'version' => '1.0.0',
	'settings' => TRUE
) )->register();

if ( $plugin->enabled() )
{
	Observer::observe( array('disqus_comments'), 'enable_disqus_comments' );

	function enable_disqus_comments($model)
	{
		echo View::factory( 'disqus/comments' );
	}
	
	if($plugin->get('counter_status', 'off') == 'on')
	{
		Observer::observe( 'advert_infopanel', 'enable_disqus_comments_counter' );

		function enable_disqus_comments_counter($model)
		{
			if(isset($model->forbid_comment) AND $model->forbid_comment == 0)
			{
				echo View::factory( 'disqus/comments_counter', array('topic' => $model) );
			}
		}
		
		Observer::observe( 'page_layout_bottom', 'enable_disqus_comments_counter_script', $plugin);
		
		function enable_disqus_comments_counter_script($plugin)
		{
			echo View::factory( 'disqus/comments_counter_script' , array(
				'plugin' => $plugin
			) );
		}
	}
}