<?php defined('SYSPATH') or die('No direct access allowed.');

class Block {

	/**
	 * 
	 * @param type string|array
	 */
	public static function not_empty( $name )
	{
		if( ! is_array($name) )
		{
			$name = array($name);
		}
		
		$blocks = Context::instance()->get_blocks();

		foreach($name as $block)
		{
			if(in_array($block, $blocks)) return FALSE;
		}
		
		return TRUE;
	}

	/**
	 * 
	 * @param string $name
	 * @param array $params
	 */
	public static function run( $name, array $params = array() )
	{
		$ctx = & Context::instance();

		$blocks = & $ctx->get_widget_by_block( $name );
		
		if( $blocks !== NULL )
		{
			if(is_array($blocks))
			{
				foreach($blocks as $block)
				{
					if($block instanceof View) 
					{
						echo $block
							->bind('ctx', $ctx)
							->set('params', $params)
							->render();
					}
					else if($block instanceof Model_Widget_Decorator ) 
					{

						echo $block
							->bind('ctx', $ctx)
							->render($params);
					}
				}
			}
			else
			{
				if($blocks instanceof View) 
				{
					echo $blocks
						->bind('ctx', $ctx)
						->set('params', $params)
						->render();
				}
				else if($blocks instanceof Model_Widget_Decorator ) 
				{

					echo $blocks
						->bind('ctx', $ctx)
						->render($params);
				}
			}
		}	
	}
	
	/**
	 * 
	 * @param string $name
	 */
	public static function def( $name ){}
	
	/**
	 * 
	 * @param string $content
	 * @return string
	 */
	public static function parse_content( $content )
	{
		$content = str_replace(' ', '', $content);
		preg_match_all("/Block::([a-z]{3})\(\'(\w+)\'\)/i", $content, $blocks);
		
		if( !empty($blocks[2]))
		{
			return $blocks[2];
		}

		return NULL;
	}
}