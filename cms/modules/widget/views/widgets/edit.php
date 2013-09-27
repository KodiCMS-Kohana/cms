<div class="widget">
<?php echo Form::open(Request::current()->uri(), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>
	<?php echo Form::hidden('id', $widget->id); ?>
	<div class="widget-header spoiler-toggle" data-spoiler=".general-spoiler">
		<h4><?php echo __('Widget Information'); ?></h4>
	</div>
	<div class="widget-content spoiler general-spoiler">
		<?php
		
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Input::factory(array(
					'name' => 'name', 'value' => $widget->name
				))
				->label(__('Widget Header'))
				->attributes('class', Bootstrap_Form_Element_Input::XXLARGE)
			));

			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Textarea::factory(array(
					'name' => 'description', 'body' => $widget->description
				))
				->label(__('Widget Description'))
			));
		?>
	</div>
	<?php if($widget->use_template): ?>
	<div class="widget-header">
		<h4><?php echo __('Widget template'); ?></h4>
	</div>
	<div class="widget-content">
		<div class="control-group">
			<label class="control-label"><?php echo __('Snippet'); ?></label>
			<div class="controls">

				<?php
				echo Form::select( 'template', $templates, $widget->template, array(
					'class' => 'input-medium', 'id' => 'WidgetTemplate'
				) );
				?>

				<?php if( ACL::check('snippet.edit')): ?>
				<?php 
				$hidden = empty($widget->template) ? 'hidden' : '';
				echo UI::button(__('Edit snippet'), array(
						'href' => Route::url('backend', array(
							'controller' => 'snippet', 
							'action' => 'edit',
							'id' => $widget->template
						)), 'icon' => UI::icon('edit'),
						'class' => 'popup fancybox.iframe btn btn-link '.$hidden, 'id' => 'WidgetTemplateButton'
					)); 
				?>
				<?php endif; ?>

				<?php if( ACL::check('snippet.add')): ?>
				<?php echo UI::button(__('Add snippet'), array(
					'href' => Route::url('backend', array(
						'controller' => 'snippet', 
						'action' => 'add'
					)),
					'icon' => UI::icon('plus'),
					'class' => 'popup fancybox.iframe btn'
				)); ?>
				<?php endif; ?>
			</div>
		</div>
		<?php if( ACL::check('widgets.cache')): ?>
		<hr />
		<?php
				echo Bootstrap_Form_Element_Control_Group::factory(array(
					'element' => Bootstrap_Form_Element_Checkbox::factory(array(
						'name' => 'caching', 'value' => 1
					), array('id' => 'caching'))
					->checked($widget->caching)
					->label(__('Cache enabled'))
				));
		?>
		
		<div id="cache_lifetime_group" class="control-group">
			<label id="bootstrap_form_element_label_QuXTwt2D" class="control-label" for="cache_lifetime">Время кеширования</label>
			<div class="controls">
				<input type="text" id="cache_lifetime" name="cache_lifetime" value="<?php echo $widget->cache_lifetime; ?>" class="input-medium">
				
				<span class="label cache-time-label" data-time="<?php echo Date::MINUTE; ?>"><?php echo __('Minute'); ?></span> 
				<span class="label cache-time-label" data-time="<?php echo Date::HOUR; ?>"><?php echo __('Hour'); ?></span>
				<span class="label cache-time-label" data-time="<?php echo Date::DAY; ?>"><?php echo __('Day'); ?></span>
				<span class="label cache-time-label" data-time="<?php echo Date::WEEK; ?>"><?php echo __('Week'); ?></span>
				<span class="label cache-time-label" data-time="<?php echo Date::MONTH; ?>"><?php echo __('Month'); ?></span>
				<span class="label cache-time-label" data-time="<?php echo Date::YEAR; ?>"><?php echo __('Year'); ?></span>
			</div>
	</div>
		<?php
				echo Bootstrap_Form_Element_Control_Group::factory(array(
					'element' => Bootstrap_Form_Element_Textarea::factory(array(
						'name' => 'cache_tags', 'body' => $widget->cache_tags()
					))
					->attributes('class', 'tags')
					->label(__('Cache tags'))
				));
		?>
		
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<div class="widget-header">
		<h4><?php echo __('Widget parameters'); ?></h4>
	</div>
	<?php if($widget->use_template): ?>
	<div class="widget-content">
		<?php echo Bootstrap_Form_Element_Control_Group::factory(array(
			'element' => Bootstrap_Form_Element_Input::factory(array(
				'name' => 'header', 'value' => $widget->header
			))
			->label(__('Header'))
			->attributes('class', Bootstrap_Form_Element_Input::BLOCK_LEVEL)
		)); ?>
	</div>
	<?php endif; ?>
	<?php echo $content; ?>

	<?php if( ACL::check('widgets.location') ): ?>
	<div class="widget-content widget-no-border-radius">
		<?php echo Bootstrap_Element_Button::factory(array(
			'href' => Route::url('backend', array(
					'controller' => 'widgets', 
					'action' => 'location',
					'id' => $widget->id)), 
			'title' => __('Widget location')
		), array('target' => 'blank'))->icon('sitemap'); ?>
	</div>
	<?php endif; ?>
	<div class="widget-footer form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>
</div>