<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal panel'
)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>

	<div class="panel-heading panel-toggler" data-target-spoiler=".general-spoiler" data-icon="exclamation-circle">
		<span class="panel-title"><?php echo __('Datasource Information'); ?></span>
	</div>
	<div class="panel-body panel-spoiler general-spoiler">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3" for="ds_name"><?php echo __('Datasource Header'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input( 'name', $ds->name, array(
					'class' => 'form-control', 'id' => 'ds_name'
				)); ?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3" for="ds_description"><?php echo __('Datasource Description'); ?></label>
			<div class="col-md-9">
				<?php echo Form::textarea( 'description', $ds->description, array(
					'class' => 'form-control', 'id' => 'ds_description', 'rows' => 4
				)); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3" for="created_by_id"><?php echo __('Datasource Author'); ?></label>
			<div class="col-md-9">
				<?php echo Form::select('created_by_id', $users, $ds->created_by_id() == 0 ? Auth::get_id() : $ds->created_by_id(), array(
					'class' => 'form-control', 'id' => 'created_by_id'
				)); ?>
			</div>
		</div>
	</div>
	
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