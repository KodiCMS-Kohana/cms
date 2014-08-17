<div class="panel-heading">
	<span class="panel-title"><?php echo __('General settings'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<?php echo Form::label('setting_disqus_id', __('Profile ID'), array('class' => 'control-label col-md-3')); ?>
		<div class="col-md-3">
			<?php echo Form::input('setting[disqus_id]', $plugin->get('disqus_id'), array(
				'id' => 'setting_disqus_id', 'class' => 'form-control'
			)); ?>
		</div>
	</div>
</div>