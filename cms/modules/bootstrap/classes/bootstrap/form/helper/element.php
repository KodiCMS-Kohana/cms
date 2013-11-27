<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * http://twitter.github.com/bootstrap/base-css.html#forms
 * @package		Twitter Bootstrap
 * @category	Form/Helper
 * @author		ButscHSter
 */
class Bootstrap_Form_Helper_Element extends Bootstrap_Helper_Elements {

	/**
	 * 
	 * @param string $text
	 * @param boolean $inline
	 * @return \Bootstrap_Abstract
	 */
	public function help_text( $text, $inline = FALSE)
	{
		$help_text = Bootstrap_Form_Helper_Help::factory(array(
				'text' => $text
			))
			->set_parent( $this );
		
		if($inline !== FALSE)
			$help_text->inline();
		
		return $this->set('help_text', $help_text);
	}
}