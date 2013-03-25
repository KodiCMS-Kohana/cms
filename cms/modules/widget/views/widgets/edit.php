<script>		
	$(function() {
		$('#WidgetTemplate').change(function() {
			var $option = $('option:selected', this);
			if($option.val() == 0)
				$('#WidgetTemplateButton').hide();
			else
				$('#WidgetTemplateButton')
					.show()
					.attr('href', '<?php echo URL::backend('snippet/edit'); ?>/' + $option.val())
		});
		
		$('body').on('post:api:snippet, put:api:snippet', function(event, response) {
			var $option = $('<option selected value="'+response.name+'">'+response.name+'</oprion>');
			$('#WidgetTemplate')
				.find('option:selected')
					.removeAttr('selected')
				.end()
				.append($option)
				.change();
		})
	})
</script>

<div class="widget">
<?php echo Form::open(Request::current()->uri(), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>
	<?php echo Form::hidden('id', $widget->id); ?>
	<div class="widget-header spoiler-toggle" data-spoiler=".general-spoiler">
		<h4><?php echo __('Widget Information'); ?> <?php echo UI::icon( 'chevron-down spoiler-toggle-icon' ); ?></h4>
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
			<div class="controls">
			<?php
			echo Form::select( 'template', $templates, $widget->template, array(
				'class' => 'input-medium', 'id' => 'WidgetTemplate'
			) );
			?>
			
			<?php 
			if( ! empty($widget->template))
				echo UI::button(__('Edit snippet'), array(
					'href' => 'snippet/edit/' . $widget->template, 'icon' => UI::icon('edit'),
					'class' => 'popup fancybox.iframe btn btn-link', 'id' => 'WidgetTemplateButton'
				)); 
			?>
				
			<?php echo UI::button(__('Add snippet'), array(
				'href' => 'snippet/add', 'icon' => UI::icon('plus'),
				'class' => 'popup fancybox.iframe btn'
			)); ?>
			</div>
		</div>
		<hr />
		<?php
				echo Bootstrap_Form_Element_Control_Group::factory(array(
					'element' => Bootstrap_Form_Element_Checkbox::factory(array(
						'name' => 'caching', 'value' => 1
					))
					->checked($widget->caching)
					->label(__('Cache enabled'))
				));

				echo Bootstrap_Form_Element_Control_Group::factory(array(
					'element' => Bootstrap_Form_Element_Input::factory(array(
						'name' => 'cache_lifetime', 'value' => (int) $widget->cache_lifetime
					))
					->label(__('Cache lifetime'))
				));

	//			echo Bootstrap_Form_Element_Control_Group::factory(array(
	//				'element' => Bootstrap_Form_Element_Textarea::factory(array(
	//					'name' => 'cache_tags', 'body' => $widget->cache_tags
	//				))
	//				->label(__('Cache tags'))
	//			));
		?>
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

	<div class="widget-content widget-no-border-radius">
		<?php echo Bootstrap_Element_Button::factory(array(
			'href' => 'widgets/location/' . $widget->id, 'title' => __('Widget location')
		), array('target' => 'blank'))->icon('sitemap'); ?>
	</div>
	<div class="widget-footer form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>
</div>