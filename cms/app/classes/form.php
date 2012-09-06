<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Form extends Kohana_Form {
	
	/**
	 * Creates a button form input. Note that the body of a button is NOT escaped,
	 * to allow images and other HTML to be used.
	 *
	 *     echo Form::button('save', 'Save Profile', array('type' => 'submit'));
	 *
	 * @param   string  input name
	 * @param   string  input value
	 * @param   array   html attributes
	 * @return  string
	 * @uses    HTML::attributes
	 */
	public static function button($name, $body, array $attributes = NULL)
	{
		// Set the input name
		$attributes['name'] = $name;
		
		if(!isset($attributes['class']))
			$attributes['class'] = 'btn';
		
		if(isset($attributes['icon']))
			$body = $attributes['icon'].' '.$body;

		return '<button'.HTML::attributes($attributes).'>'.$body.'</button>';
	}

	public static function actions($page) 
	{
		return	Form::button('continue', HTML::icon('ok') .' '. __('Save and Continue editing'), array(
				'class' => 'btn btn-large'
			))
			. Form::button('commit', HTML::icon('ok') .' '.  __('Save and Close'), array(
				'class' => 'btn btn-info'
			))
			. HTML::button(URL::site($page), __('Cancel'), 'remove', 'btn btn-danger');
	}
}