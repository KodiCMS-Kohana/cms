<script type="text/javascript">
$(function() {
	var $fields = $('#section-fields input'),
		$checked_fields = $fields.filter(':checked');
	
	$fields.change(function(){
		if($fields.filter(':checked').size() == 0) {
			$('#remove-fields').attr('disabled', 'disabled');
		} else {
			$('#remove-fields').removeAttr('disabled');
		}
		
		$checked_fields = $fields.filter(':checked');
	}).change();
	
	$('#remove-fields').on('click', function() {
		if($checked_fields.length < 1) return false;
		
		if( ! confirm(__('Are you sure?')))
			return;
		
		Api.delete('/datasource/hybrid-field', $checked_fields.serialize(), function(response) {
			for(i in response.response) {
				$('#field-' + response.response[i]).remove();
			}
		});
		
		return false;
	});
	
	$('input[name^="in_headline"]').on('change', function() {
		var id = parseInt($(this).prop('name').match(/.*\[(\d+)\]/)[1]);
		if( ! id) return;
		
		if($(this).checked()) {
			Api.post('/datasource/hybrid-field.headline', {id: id}, function(response) {});
		} else {
			Api.delete('/datasource/hybrid-field.headline', {id: id}, function(response) {});
		}
	});
	
	$('input[name^="index_type"]').on('change', function() {
		var id = parseInt($(this).prop('name').match(/.*\[(\d+)\]/)[1]);
		if( ! id) return;
		
		if($(this).checked()) {
			Api.post('/datasource/hybrid-field.index_type', {id: id}, function(response) {});
		} else {
			Api.delete('/datasource/hybrid-field.index_type', {id: id}, function(response) {});
		}
	});
});
</script>

<div class="widget-header">
	<h4><?php echo __('Datasource Fields'); ?></h4>
</div>
<div class="widget-content widget-nopad">
	<table id="section-fields" class="table table-striped table-hover">
		<colgroup>
			<?php if(Acl::check($ds->type().$ds->id().'.field.remove')): ?>
			<col width="30px" />
			<?php endif; ?>
			<col width="50px" />
			<col width="100px" />
			<col width="200px" />
			<col width="100px" />
			<col width="150px" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<?php if(Acl::check($ds->type().$ds->id().'.field.remove')): ?>
				<th></th>
				<?php endif; ?>
				<th><?php echo __('Field position'); ?></th>
				<th><?php echo __('Field key'); ?></th>
				<th><?php echo __('Field header'); ?></th>
				<th><?php echo __('Field type'); ?></th>
				<th><?php echo __('Show in headline'); ?></th>
				<th><?php echo __('MySQL index'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php if(Acl::check($ds->type().$ds->id().'.field.remove')): ?>
				<td class="f">
					<?php echo Form::checkbox('field[]', 'id', FALSE, array(
						'disabled' => 'disabled'
					)); ?>
				</td>
				<?php endif; ?>
				<td class="position">0</td>
				<td class="sys">ID</td>
				<td>ID</td>
				<td><?php echo UI::label('integer'); ?></td>
				<td><?php echo Form::checkbox('', 1, TRUE, array('disabled' => 'disabled')); ?></td>
				<td><?php echo Form::checkbox('', 1, TRUE, array('disabled' => 'disabled')); ?></td>
			</tr>
			<tr>
				<?php if(Acl::check($ds->type().$ds->id().'.field.remove')): ?>
				<td class="f">
					<?php echo Form::checkbox('field[]', 'header', FALSE, array(
						'disabled' => 'disabled'
					)); ?>
				</td>
				<?php endif; ?>
				<td class="position">0</td>
				<td class="sys">header</td>
				<td><?php echo __('Header'); ?></td>
				<td><?php echo UI::label('string'); ?></td>
				<td><?php echo Form::checkbox('', 1, TRUE, array('disabled' => 'disabled')); ?></td>
				<td></td>
			</tr>

			<?php foreach($record->fields() as $field): ?>
			<tr id="field-<?php echo $field->id; ?>">
				<?php if(Acl::check($ds->type().$ds->id().'.field.remove')): ?>
				<td class="f">
					<?php 
					$attrs = array('id' => $field->name);
					echo Form::checkbox('field[]', $field->id, FALSE, $attrs); ?>
				</td>
				<?php endif; ?>
				<td class="position"><?php echo $field->position; ?></td>
				<td class="sys">
					<label for="<?php echo $field->name; ?>">
						<?php echo substr($field->name, 2); ?>
					</label>
				</td>
				<td>
					<?php if(Acl::check($ds->type().$ds->id().'.field.edit')): ?>
					<?php echo HTML::anchor(Route::get('datasources')->uri(array(
						'controller' => 'field',
						'directory' => 'hybrid',
						'action' => 'edit',
						'id' => $field->id
					)), $field->header  ); ?>
					<?php else: ?>
					<strong><?php echo $field->header; ?> </strong>
					<?php endif; ?>
				</td>
				<td>
					<?php echo UI::label($field->type); ?>
				</td>
				<td>
					<?php 
					$attrs = array();
					if ( ! Acl::check($ds->type().$ds->id().'.field.edit'))
					{
						$attrs['disabled'] = 'disabled';
					}
					
					echo Form::checkbox('in_headline['.$field->id.']', 1, (bool) $field->in_headline, $attrs); ?>
				</td>
				<td>
				<?php if($field->is_indexable())
				{
					$attrs = array();
					if ( ! Acl::check($ds->type().$ds->id().'.field.edit'))
					{
						$attrs['disabled'] = 'disabled';
					}

					echo Form::checkbox('index_type['.$field->id.']', 1, $field->is_indexed(), $attrs); 
				} ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div class="widget-header">
	<div class="btn-group">
		<?php if(Acl::check($ds->type().$ds->id().'.field.edit')): ?>
		<?php echo UI::button(__('Add field'), array(
			'href' => Route::get('datasources')->uri(array(
				'controller' => 'field',
				'directory' => 'hybrid',
				'action' => 'add',
				'id' => $ds->id()
			)),
			'icon' => UI::icon('plus'),
			'class' => 'btn fancybox'
		)); ?>
		<?php endif; ?>
		
		<?php if(Acl::check($ds->type().$ds->id().'.field.remove')): ?>
		<?php echo UI::button(__('Remove fields'), array(
			'icon' => UI::icon('minus icon-white'), 'id' => 'remove-fields',
			'class' => 'btn btn-danger'
		)); ?>
		<?php endif; ?>
	</div>
</div>
<?php echo View::factory('widgets/backend/blocks/sorting', array(
	'ds_id' => $ds->id(),
	'doc_order' => $ds->headline()->sorting()
));?>

