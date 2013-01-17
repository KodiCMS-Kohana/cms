<?php defined('SYSPATH') or die('No direct access allowed.');

$plugin = Plugins_Item::factory( array(
	'id' => 'messages',
	'title' => 'User messages',
	'description' => 'Provides user messages system.',
	'javascripts' => 'messages.js'
) )->register();

if($plugin->enabled())
{	
	if(IS_BACKEND)
	{
		
	}
}