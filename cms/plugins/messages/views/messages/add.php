<div class="map widget">
	<?php echo Form::open(NULL, array('class' => 'form-horizontal')); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="widget-title">
		<div class="control-group">
			<label class="control-label title"><?php echo __( 'Message title' ); ?></label>
			<div class="controls">
				<?php echo Form::input( 'title', NULL, array(
					'class' => 'span12 input-title focus'
				) ); ?>
			</div>
		</div>
		
		<br />
		
		<div class="control-group">
			<label class="control-label"><?php echo __( 'To' ); ?></label>
			<div class="controls">
				<?php echo Form::input( 'to', NULL, array(' autocomplete' => 'off') ); ?>
			</div>
		</div>
	</div>
	<div class="widget-content widget-no-border-radius">
		
		<div class="row-fluid">
			<?php echo Form::textarea('content', NULL, array('class' => 'span12')); ?>
		</div>
		
	</div>
	<div class="widget-footer form-actions">
		<?php echo UI::button(__('Send message'), array('class' => 'btn btn-large')); ?>
	</div>
	<?php echo Form::close(); ?>
</div>