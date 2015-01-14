<tr>
	<th>
		<?php if (ACL::check('widgets.edit')): ?>
		<?php echo HTML::anchor(Route::get('backend')->uri(array(
			'controller' => 'widgets', 'action' => 'edit', 'id' => $widget->id
		)), $widget->name, array('target' => 'blank')); ?>
		<?php else: ?>
		<?php echo UI::icon('lock'); ?> <?php echo $widget->name; ?>
		<?php endif; ?>
		<?php if (!empty($widget->description)): ?>
		<p class="muted"><?php echo $widget->description; ?></p>
		<?php endif; ?>
	</th>
	<td>
		<?php if (ACL::check('widgets.location')): ?>
		<?php echo Form::input('widget[' . $widget->id . '][position]', (int) $widget->position, array('maxlength' => 4, 'size' => 4, 'class' => 'form-control text-right') );?>
		<?php else: ?>
		<span class="label label-success"><?php echo __('Position: :position', array(
			':block_name' => $widget->block
		)); ?></span>
		<?php endif; ?>
	</td>
	<td>
		<?php if (ACL::check('widgets.location')): ?>
		<div class="input-group">
			<?php echo Form::hidden('widget[' . $widget->id . '][block]', ! empty($widget->block) ? $widget->block : 0, array(
				'class' => 'widget-blocks', 
				'data-layout' => $page->layout())); ?>

			<div class="input-group-btn">
			<?php echo UI::button(NULL, array(
				'href' => Route::get('backend')->uri(array(
					'controller' => 'widgets', 
					'action' => 'location',
					'id' => $widget->id)), 
				'icon' => UI::icon('sitemap'),
				'class' => 'btn-primary popup fancybox.iframe',
				'target' => 'blank'
			)); ?>
			</div>
		</div>
		<?php else: ?>
		<span class="label label-success"><?php echo __('Block: :block_name', array(
			':block_name' => $widget->block
		)); ?></span>
		<?php endif; ?>
	</td>
</tr>