<script type="text/javascript">
	var DS_ID = '<?php echo $ds->id(); ?>';
</script>

<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal panel'
)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>
	
	<div class="panel-heading" data-icon="info">
		<span class="panel-title"><?php echo __('Datasource Information'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group form-group-lg">
			<label class="control-label col-md-3" for="name"><?php echo __('Datasource Header'); ?></label>
			<div class="col-md-9">
				<?php echo Form::input('name', $ds->name, array(
					'class' => 'form-control', 'id' => 'name'
				)); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3" for="description"><?php echo __('Datasource Description'); ?></label>
			<div class="col-md-9">
				<?php echo Form::textarea('description', $ds->description, array(
					'class' => 'form-control', 'id' => 'description'
				)); ?>
			</div>
		</div>
	</div>
	
	<div class="form-actions panel-footer">
		<?php echo UI::actions(NULL, Datasource_Section::uri()); ?>
	</div>
<?php echo Form::close(); ?>