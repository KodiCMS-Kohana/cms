<script>
	var DS_ID = '<?php echo $ds->id(); ?>';
</script>

<div class="widget">
<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal'
)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>

	<div class="widget-header spoiler-toggle" data-spoiler=".general-spoiler">
		<h4><?php echo __('Datasource Information'); ?></h4>
	</div>
	<div class="widget-content spoiler general-spoiler">
		<div class="control-group">
			<label class="control-label title" for="ds_name"><?php echo __('Datasource Header'); ?></label>
			<div class="controls">
				<?php echo Form::input( 'name', $ds->name, array(
					'class' => 'input-title input-block-level', 'id' => 'ds_name'
				) ); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="ds_key"><?php echo __('Datasource Key'); ?></label>
			<div class="controls">
				<?php echo Form::input( 'key', $ds->key, array(
					'class' => 'input-xlarge', 'id' => 'ds_key', 'disabled'
				) ); ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="ds_description"><?php echo __('Datasource Description'); ?></label>
			<div class="controls">
				<?php echo Form::textarea( 'description', $ds->description, array(
					'class' => 'input-block-level', 'id' => 'ds_description', 'rows' => 4
				) ); ?>
			</div>
		</div>
		
	</div>
	
	<?php echo View::factory('datasource/data/hybrid/blocks/fields', array(
		'record' => $ds->get_record(), 'ds' => $ds
	)); ?>
	
	<div class="widget-header spoiler-toggle" data-spoiler=".template-spoiler">
		<h4><?php echo __('Document template'); ?></h4>
	</div>
	<div class="widget-content spoiler template-spoiler">
		<div class="control-group">
			<div class="controls">

				<?php
				echo Form::select( 'template', Model_File_Snippet::html_select(), $ds->template, array(
					'class' => 'input-medium', 'id' => 'WidgetTemplate'
				) );
				?>
				
				<div class="btn-group">
				<?php if( ACL::check('snippet.edit')): ?>
				<?php 
				$hidden = empty($ds->template) ? 'hidden' : '';
				echo UI::button(__('Edit snippet'), array(
						'href' => Route::url('backend', array(
							'controller' => 'snippet', 
							'action' => 'edit',
							'id' => $ds->template
						)), 'icon' => UI::icon('edit'),
						'class' => 'popup fancybox.iframe btn btn-primary '.$hidden, 'id' => 'WidgetTemplateButton'
					)); 
				?>
				<?php endif; ?>

				<?php if( ACL::check('snippet.add')): ?>
				<?php echo UI::button(__('Add snippet'), array(
					'href' => Route::url('backend', array(
						'controller' => 'snippet', 
						'action' => 'add'
					)),
					'icon' => UI::icon('plus'),
					'class' => 'popup fancybox.iframe btn btn-success'
				)); ?>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="widget-header spoiler-toggle" data-spoiler=".indexer-spoiler">
		<h4><?php echo __('Search indexation'); ?></h4>
	</div>
	<div class="widget-content spoiler indexer-spoiler">
		<div class="control-group">
			<div class="controls">
				<label class="checkbox"><?php echo Form::checkbox( 'is_indexable', 1, $ds->is_indexable() ); ?> <?php echo __('Is indexable'); ?></label>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="search_intro_field"><?php echo __('Index document intro'); ?></label>
			<div class="controls">
				<?php echo Form::select('search_intro_field',  array(__('--- none ---')) + $ds->record_fields_array(), $ds->search_intro_field); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="search_index_fields"><?php echo __('Index document fields'); ?></label>
			<div class="controls">
				<?php echo Form::select('search_index_fields[]', $ds->record_fields_array(), (array) $ds->search_index_fields, array(
					'class' => 'input-block-level'
				)); ?>
			</div>
		</div>
	</div>
	<div class="form-actions widget-footer">
		<?php echo UI::actions(NULL, Route::url('datasources', array(
			'controller' => 'data',
			'directory' => 'datasources'
		))); ?>
	</div>
<?php echo Form::close(); ?>
</div>