<div class="widget">
<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal'
)); ?>
	<div class="widget-header">
		<h4><?php echo __('General Information'); ?></h4>
	</div>
	<div class="widget-content">
		<div class="control-group">
			<label class="control-label title" for="ds_name"><?php echo __('Datasource Header'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'ds_name', Arr::get($data, 'ds_name'), array(
					'class' => 'input-xlarge input-title slug-generator span12', 'id' => 'ds_name', 'data-separator' => '_'
				) );
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="ds_description"><?php echo __('Datasource Description'); ?></label>
			<div class="controls">
				<?php
				echo Form::textarea( 'ds_description', Arr::get($data, 'ds_description'), array(
					'class' => 'input-xlarge', 'id' => 'ds_description', 'rows' => 3
				) );
				?>
			</div>
		</div>
	</div>
	
	<?php
	try
	{
		echo View::factory('datasource/'.$type.'/create', array('data' => data));
	}
	catch(Exception $e) {} ?>
	
	<div class="widget-footer form-actions">
		<?php echo UI::button( __('Create section'), array(
			'icon' => UI::icon( 'plus'), 'class' => 'btn btn-large'
		)); ?>
	</div>
</form>

<?php echo Form::close(); ?>
</div>