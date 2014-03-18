<?php if(!Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
<script>
$(function() {
	$('input,textarea,select').attr('disabled', 'disabled');
})
</script>
<?php endif; ?>

<div class="outline">
	<div class="widget outline_inner">
	
	<?php if(Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
	<?php echo Form::open(Request::current()->url() . URL::query(array('id' => $doc->id)), array(
		'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
	)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>
	<?php echo Form::hidden('id', $doc->id); ?>
	<?php else: ?>
	<div class="form-horizontal">
	<?php endif; ?>
	<div class="widget-title">
		<div class="control-group">
			<label class="control-label title"><?php echo __('Header'); ?></label>
			<div class="controls">
				<?php echo Form::input('header', $doc->header, array(
					'class' => 'input-title input-block-level slug-generator', 'data-slug' => '.from-header'
				)); ?>
			</div>
			
			<div class="controls">
				<?php echo View::factory('datasource/data/hybrid/document/fields/published', array(
					'doc' => $doc
				)); ?>
			</div>	
		</div>		
	</div>
		
	<br />
		
	<?php foreach ($record->fields() as $key => $field): ?>
		<?php echo View::factory('datasource/data/hybrid/document/fields/' . $field->type, array(
			'value' => $doc->fields[$key], 
			'field' => $field,
			'doc' => $doc
		)); ?>
	<?php endforeach; ?>

		
	<?php if(Acl::check('hybrid'.$ds->id().'.document.edit')): ?>
	<div class="form-actions widget-footer">
		<?php echo UI::actions(TRUE, Route::url('datasources', array(
			'controller' => 'data',
			'directory' => 'datasources'
		)) . URL::query(array('ds_id' => $ds->id()), FALSE)); ?>
	</div>
	<?php echo Form::close(); ?>
	<?php else: ?>
	</div>
	<?php endif; ?>
</div></div>