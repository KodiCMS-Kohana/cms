<div class="panel">
	<?php echo Form::open(Request::current()->uri()); ?>
	<?php if (Request::initial()->query('type') != 'iframe'): ?>
	<div class="panel-heading">
		<h3 class="no-margin-vr">
			<small>&larr; <?php echo __('Back to field settings:'); ?></small>
			<?php echo HTML::anchor(Route::get('datasources')->uri(array(
				'directory' => 'hybrid',
				'controller' => 'field',
				'action' => 'edit',
				'id' => $field->id
			)), $field->header); ?>
		</h3>
	</div>
	<?php endif; ?>

	<table class="table table-primary table-striped">
		<colgroup>
			<col width="300px" />
			<col width="250px" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Widget name'); ?></th>
				<th><?php echo __('Type'); ?></th>
				<th><?php echo __('Status'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($widgets as $widget): ?>
			<tr>
				<th>
					<?php if (ACL::check('widgets.edit')): ?>
					<?php echo HTML::anchor(Route::get('backend')->uri(array(
						'controller' => 'widgets', 
						'action' => 'edit',
						'id' => $widget->id)), $widget->name); ?>
					<?php else: ?>
					<?php echo UI::icon('lock'); ?> <?php echo $widget->name; ?>
					<?php endif; ?>
				</th>
				<td><?php echo UI::label($widget->type(FALSE)); ?></td>
				<td><?php echo Form::checkbox('widget[' . $widget->id . ']', $field->id, in_array($field->id,  $widget->doc_fields)); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="panel-footer form-actions">
		<?php echo UI::button( __('Save'), array(
			'icon' => UI::icon('check'), 
			'class' => 'btn-lg btn-primary',
			'data-hotkeys' => 'ctrl+s'
		)); ?>
	</div>
	<?php echo Form::close(); ?>
</div>


