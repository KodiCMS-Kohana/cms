<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * Build layout blocks
 */
$layouts = Model_File_Layout::find_all();
		
foreach($layouts as $layout)
{
	$layout->rebuild_blocks();
}