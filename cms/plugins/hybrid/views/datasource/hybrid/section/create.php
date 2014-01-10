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
				echo Form::input( 'name', NULL, array(
					'class' => 'input-xlarge input-title slug-generator span12', 'id' => 'ds_name', 'data-separator' => '_'
				) );
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="ds_key"><?php echo __('Datasource Key'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'key', NULL, array(
					'class' => 'input-xlarge slug', 'id' => 'ds_key'
				) );
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="ds_description"><?php echo __('Datasource Description'); ?></label>
			<div class="controls">
				<?php
				echo Form::textarea( 'description', NULL, array(
					'class' => 'input-xlarge', 'id' => 'ds_description', 'rows' => 3
				) );
				?>
			</div>
		</div>
	</div>
	<div class="widget-footer form-actions">
		<?php echo UI::button( __('Create hybrid section'), array(
			'icon' => UI::icon( 'plus'), 'class' => 'btn btn-large'
		)); ?>
	</div>
</form>

<?php echo Form::close(); ?>
</div>