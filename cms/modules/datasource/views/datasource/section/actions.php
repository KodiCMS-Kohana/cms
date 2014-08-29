<div class="panel-heading">
<?php if(ACL::check($ds_type.$ds_id.'.document.edit')):?>
<?php echo UI::button(__('Create Document'), array(
	'href' => Route::get('datasources')->uri(array(
		'controller' => 'document',
		'directory' => $ds_type,
		'action' => 'create'
	)) . URL::query(array('ds_id' => $ds_id)),
	'icon' => UI::icon( 'plus' ),
	'data-hotkeys' => 'ctrl+a',
	'class' => 'btn-primary'
)); ?>
<?php endif; ?>

<?php if(ACL::check($ds_type.$ds_id.'.document.edit')):?>
	<div class="panel-heading-controls col-md-3">
		<div class="">
			<div class="input-group">
				<?php echo Form::select('doc_actions', array(
					__('Actions'), 
					'remove' => __('Remove'), 
					'publish' => __('Publish'), 
					'unpublish' => __('Unpublish')), NULL, array(
						'id' => 'doc-actions', 
						'class' => 'form-control no-script',
						'data-section' => $ds_type
					)); ?>

				<div class="input-group-btn">
					<?php echo UI::button(__('Apply'), array(
						'id' => 'apply-doc-action', 
						'class' => 'btn-success'
					)); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
</div>