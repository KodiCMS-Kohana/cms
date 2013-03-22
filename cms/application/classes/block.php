<?php defined('SYSPATH') or die('No direct access allowed.');

class Block {

	public static function run( $name, array $params = array() )
	{
		$ctx = Context::instance();

		$block = $ctx->get_widget_by_block( $name );
		
		if( $block !== NULL)
		{
			if($block instanceof View) 
			{
				echo $block->set('params', $params)->render();
			}
			else if($block instanceof Model_Widget_Decorator ) 
			{
				
				echo $block->render($params);
			}
		}	
	}
	
	public static function parse_content( $content )
	{
		$content = str_replace(' ', '', $content);
		preg_match_all("/Block::run\(\'(\w+)\'\)/i", $content, $blocks);
		
		if( !empty($blocks[1]))
		{
			return $blocks[1];
		}
		return array();
	}
}