<?php echo Assets_Package::load('editable'); ?>
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
	
	$(document).on('change', 'input[name^="in_headline"]', function() {
		var id = parseInt($(this).prop('name').match(/.*\[(\d+)\]/)[1]);
		if(!id) return;
		
		if($(this).checked()) {
			Api.post('/datasource/hybrid-field.headline', {id: id}, function(response) {});
		} else {
			Api.delete('/datasource/hybrid-field.headline', {id: id}, function(response) {});
		}
	});
	
	$(document).on('change', 'input[name^="index_type"]', function() {
		var id = parseInt($(this).prop('name').match(/.*\[(\d+)\]/)[1]);
		if( ! id) return;
		
		if($(this).checked()) {
			Api.post('/datasource/hybrid-field.index_type', {id: id}, function(response) {});
		} else {
			Api.delete('/datasource/hybrid-field.index_type', {id: id}, function(response) {});
		}
	});

	<?php if($ds->has_access('field.edit')): ?>
	$('.editable-position').editable({
		title: __('Field position'),
		send: 'always',
		highlight: false,
		tpl: '<input type="text" size="5">',
		ajaxOptions: {
			dataType: 'json'
		},
		params: function(params) {
			params.id = $(this).closest('tr').data('id');
			return params;
		},
		url: Api.build_url('datasource/hybrid-field.position'),
		success: function(response, newValue) {
			if(response.response) {
				$(this).text(response.response);
				sort_field_rows();
			}
		}
	});
	<?php endif; ?>
});

	
function sort_field_rows() {
	var $table = $('#section-fields'),
		$rows = $('tbody > tr[data-id]', $table);

	$rows.sort(function(a, b) {
		var keyA = $('td.position', a).text();
		var keyB = $('td.position', b).text();
		return (parseInt(keyA) > parseInt(keyB)) ? 1 : 0;
	});

	$rows.each(function(index, row){
		$table.append(row);                  // append rows after sort
	});
}
</script>

<div class="panel-heading" data-icon="th-list">
	<span class="panel-title"><?php echo __('Datasource Fields'); ?></span>
</div>
<table id="section-fields" class="table table-primary table-striped table-hover">
	<colgroup>
		<?php if($ds->has_access('field.remove')): ?>
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
			<?php if($ds->has_access('field.remove')): ?>
			<td></td>
			<?php endif; ?>
			<td><?php echo __('Field position'); ?></td>
			<td><?php echo __('Field key'); ?></td>
			<td><?php echo __('Field header'); ?></td>
			<td><?php echo __('Field type'); ?></td>
			<td><?php echo __('Show in headline'); ?></td>
			<td><?php echo __('MySQL index'); ?></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<?php if($ds->has_access('field.remove')): ?>
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
			<?php if($ds->has_access('field.remove')): ?>
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
		<tr id="field-<?php echo $field->id; ?>" data-id="<?php echo $field->id; ?>">
			<?php if($ds->has_access('field.remove')): ?>
			<td class="f">
				<?php 
				$attrs = array('id' => $field->name);
				echo Form::checkbox('field[]', $field->id, FALSE, $attrs); ?>
			</td>
			<?php endif; ?>
			<td class="position"><span class="editable-position"><?php echo $field->position; ?></span></td>
			<td class="sys">
				<label for="<?php echo $field->name; ?>">
					<?php echo substr($field->name, 2); ?>
				</label>
			</td>
			<td>
				<?php if($ds->has_access('field.edit')): ?>
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
				if (!$ds->has_access('field.edit'))
				{
					$attrs['disabled'] = 'disabled';
				}

				echo Form::checkbox('in_headline[' . $field->id . ']', 1, (bool) $field->in_headline, $attrs); 
			?>
			</td>
			<td>
			<?php 
			if($field->is_indexable())
			{
				$attrs = array();
				if (!$ds->has_access('field.edit'))
				{
					$attrs['disabled'] = 'disabled';
				}

				echo Form::checkbox('index_type[' . $field->id . ']', 1, $field->is_indexed(), $attrs);
			} ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<div class="panel-footer">
	<div class="btn-group">
		<?php if($ds->has_access('field.edit')): ?>
		<?php echo UI::button(__('Add field'), array(
			'href' => Route::get('datasources')->uri(array(
				'controller' => 'field',
				'directory' => 'hybrid',
				'action' => 'add',
				'id' => $ds->id(),
			)),
			'icon' => UI::icon('plus'),
			'class' => 'btn-primary fancybox'
		)); ?>
		<?php endif; ?>
		
		<?php if($ds->has_access('field.remove')): ?>
		<?php echo UI::button(__('Remove fields'), array(
			'icon' => UI::icon('trash-o'), 'id' => 'remove-fields',
			'class' => 'btn-danger'
		)); ?>
		<?php endif; ?>
	</div>
</div>
<?php echo View::factory('widgets/backend/blocks/sorting', array(
	'ds_id' => $ds->id(),
	'doc_order' => $ds->headline()->sorting()
));?>

