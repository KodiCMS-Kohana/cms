<tr>
	<th>
		<?php if( ACL::check( 'widgets.edit')): ?>
		<?php echo HTML::anchor(Route::url('backend', array(
			'controller' => 'widgets', 'action' => 'edit', 'id' => $widget->id
		)), $widget->name, array('target' => 'blank')); ?>
		<?php else: ?>
		<?php echo UI::icon('lock'); ?> <?php echo $widget->name; ?>
		<?php endif; ?>
		<?php if(!empty($widget->description)): ?>
		<p class="muted"><?php echo $widget->description; ?></p>
		<?php endif; ?>
	</th>
	<td>
		<?php if( ACL::check( 'widgets.location')): ?>
		<?php
		echo Form::hidden('widget['.$widget->id.'][block]', !empty($widget->block) ? $widget->block : 0, array('class' => 'widget-select-block'));
		?>
		<?php endif; ?>
	</td>
</tr>