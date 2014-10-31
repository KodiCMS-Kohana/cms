<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal panel'
)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>

	<?php echo View::factory('datasource/section/information_form', array(
		'users' => $users,
		'ds' => $ds
	)); ?>
	
	<?php echo View::factory('datasource/hybrid/blocks/fields', array(
		'record' => $ds->record(), 'ds' => $ds
	)); ?>
	
	<?php echo View::factory('helper/snippet_select', array(
		'header' => __('Document template'),
		'template' => $ds->template,
	)); ?>

	<div class="panel-heading panel-toggler" data-target-spoiler=".indexer-spoiler" data-hotkeys="shift+s" data-icon="search">
		<span class="panel-title"><?php echo __('Search indexation'); ?></span>
	</div>
	<div class="panel-body panel-spoiler indexer-spoiler">
		<div class="form-group">
			<div class="col-md-offset-3 col-md-9">
				<div class="checkbox">
					<label><?php echo Form::checkbox( 'is_indexable', 1, $ds->is_indexable() ); ?> <?php echo __('Is indexable'); ?></label>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3" for="search_intro_field"><?php echo __('Index document intro'); ?></label>
			<div class="col-md-3">
				<?php echo Form::select('search_intro_field',  array(__('--- none ---')) + $ds->record_fields_array(), $ds->search_intro_field); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3" for="search_index_fields"><?php echo __('Index document fields'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('search_index_fields[]', $ds->record_fields_array(), (array) $ds->search_index_fields); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3" for="search_index_doc_id_fields"><?php echo __('Document ID fields'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('search_index_doc_id_fields[]', $ds->record_fields_array(), (array) $ds->search_index_doc_id_fields); ?>
			</div>
		</div>
	</div>
	<div class="form-actions panel-footer">
		<?php echo UI::actions(NULL, Route::get('datasources')->uri(array(
			'controller' => 'data',
			'directory' => 'datasources'
		))); ?>
	</div>
<?php echo Form::close(); ?>