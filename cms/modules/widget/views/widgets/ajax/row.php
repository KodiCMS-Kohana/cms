<tr>
	<th>
		<?php echo HTML::anchor('widgets/edit/' . $widget->id, $widget->name, array('target' => 'blank')); ?>
		<?php if(!empty($widget->description)): ?>
		<p class="muted"><?php echo $widget->description; ?></p>
		<?php endif; ?>
	</th>
	<td>
		<?php
		echo Form::hidden('widget['.$widget->id.'][block]', !empty($widget->block) ? $widget->block : 0, array('class' => 'widget-select-block'));
		?>
	</td>
</tr>