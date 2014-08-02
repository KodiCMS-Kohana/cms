<div class="widget-content">
	<?php
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Select::factory(array(
				'name' => 'page_id', 'options' => $select
			))
			->attributes('class', Bootstrap_Form_Element_Input::XXLARGE)
			->selected($widget->page_id)
			->label(__('Page'))
		));
	?>
	<hr />

	<?php
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Checkbox::factory(array(
				'name' => 'include_users_object', 'value' => 1
			))
			->checked($widget->include_users_object == 1)
			->label(__('Include users object'))
		));
	?>
</div>