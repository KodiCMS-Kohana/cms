<?php if( ACL::check('setting.api')): ?>
<div class="widget-header spoiler-toggle" data-spoiler=".api-settings">
	<h3><?php echo __('API'); ?></h3>
</div>
<div class="widget-content spoiler api-settings">
	<div class="control-group">
		<label class="control-label"><?php echo __( 'API enable' ); ?></label>
		<div class="controls">
			<?php
			echo Form::select( 'setting[api_mode]', array( 'yes' => __( 'Yes' ), 'no' => __( 'No' ) ), Setting::get( 'api_mode' ));
			?>
		</div>
	</div>
</div>
<?php endif; ?>