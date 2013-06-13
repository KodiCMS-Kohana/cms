<tr>
	<th>
		<?php echo HTML::anchor('widgets/edit/' . $widget->id, $widget->name, array('target' => 'blank')); ?>
		<?php if(!empty($widget->description)): ?>
		<p class="muted"><?php echo $widget->description; ?></p>
		<?php endif; ?>
	</th>
	<td>
		<?php
		echo Form::select('widget['.$widget->id.'][block]', array(), NULL, array('class' => 'widget-select-block no-script')); 
		?>
	</td>
</tr>