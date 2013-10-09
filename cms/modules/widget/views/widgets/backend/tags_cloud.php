<div class="widget-content widget-no-border-radius">
	<?php
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Input::factory(array(
				'name' => 'min_size', 'value' => $widget->min_size
			))
			->label(__('Min font-size (px)'))
		));
	?>
	
	<?php
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Input::factory(array(
				'name' => 'max_size', 'value' => $widget->max_size
			))
			->label(__('Max font-size (px)'))
		));
	?>
	<hr />
	<?php
		echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Select::factory(array(
				'name' => 'order_by', 'options' => array(
					'name_asc' => __('Tag name asc'),
					'name_desc' => __('Tag name desc'),
					'count_asc' => __('Count tags asc'),
					'count_desc' => __('Count tags desc'),
				)
			))
			->attributes('class', Bootstrap_Form_Element_Input::XXLARGE)
			->selected($widget->order_by)
			->label(__('Order by'))
		));
	?>
	
</div>