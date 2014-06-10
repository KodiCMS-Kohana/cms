<div class="widget-content">
	<div class="control-group">
		<label class="control-label" for="email_id_ctx"><?php echo __('Email ID (Ctx)'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'email_id_ctx', $widget->email_id_ctx, array(
				'class' => 'input-small', 'id' => 'email_id_ctx'
			) ); ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="next_url"><?php echo __('Next page (URI)'); ?></label>
		<div class="controls">
			<?php echo Form::input( 'next_url', $widget->get('next_url'), array(
				'class' => 'input-large', 'id' => 'next_url'
			) ); ?>
		</div>
	</div>
</div>