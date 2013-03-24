<div class="widget-content">
	<?php 
	
	echo Bootstrap_Form_Element_Control_Group::factory(array(
		'element' => Bootstrap_Form_Element_Input::factory(array(
			'name' => 'login_field', 'value' => $widget->get('login_field')
		))
		->label(__('Login ID (POST)'))
	));
	
	echo Bootstrap_Form_Element_Control_Group::factory(array(
		'element' => Bootstrap_Form_Element_Input::factory(array(
			'name' => 'password_field', 'value' => $widget->get('password_field')
		))
		->label(__('Password ID (POST)'))
	));
	
	echo Bootstrap_Form_Element_Control_Group::factory(array(
		'element' => Bootstrap_Form_Element_Input::factory(array(
			'name' => 'next_url', 'value' => $widget->get('next_url')
		))
		->label(__('Next page by default (URI)'))
		->attributes('class', Bootstrap_Form_Element_Input::XXLARGE)
	));
	?>
	<hr />
	<?php
	echo Bootstrap_Form_Element_Control_Group::factory(array(
		'element' => Bootstrap_Form_Element_Checkbox::factory(array(
			'name' => 'remember', 'value' => 1
		))
		->checked($widget->remember)
		->label(__('Allow Autologin'))
	));
	
	echo Bootstrap_Form_Element_Control_Group::factory(array(
		'element' => Bootstrap_Form_Element_Input::factory(array(
			'name' => 'remember_field', 'value' => $widget->get('remember_field')
		))
		->label(__('Autologin ID'))
	));
	?>
</div>