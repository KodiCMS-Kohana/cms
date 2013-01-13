<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugins_Item::factory( array(
	'id' => 'disqus',
	'title' => 'Disqus',
	'description' => 'Disqus is a global comment system that improves discussion on websites and connects conversations across the web.',
	'version' => '1.0.0',
	'settings' => TRUE
) )->register();

if ( $plugin->enabled() )
{
	Observer::observe( 'disqus_comments', 'enable_disqus_comments' );

	function enable_disqus_comments()
	{
		echo View::factory( 'disqus/comments' );
	}
}