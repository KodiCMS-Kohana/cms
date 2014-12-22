<?php echo Form::open('api-dashboard.widget', array(
	'class' => array(Form::HORIZONTAL, 'panel', 'widget-settings')
)); ?>
	<?php echo Form::hidden('id', $widget->id); ?>
	<div class="panel-heading">
		<span class="panel-title" data-icon="cogs"><?php echo __('Widget parameters'); ?></span>
	</div>

	<div class="panel-body">
		<div class="form-group">
			<label class="control-label col-xs-3"><?php echo __('Header'); ?></label>
			<div class="col-xs-9">
				<?php echo Form::input('header', $widget->header, array(
					'class' => 'form-control'
				)); ?>
			</div>
		</div>
	</div>

	<?php echo $widget->fetch_backend_content(); ?>

	<div class="panel-footer form-actions">
		<?php echo UI::button(__('Save settings'), array(
			'icon' => UI::icon('plus'),
			'class' => 'btn-lg btn-primary btn-save-settings'
		)); ?>
	</div>
<?php echo Form::close(); ?>
<script>
cms.ui.init();
</script>