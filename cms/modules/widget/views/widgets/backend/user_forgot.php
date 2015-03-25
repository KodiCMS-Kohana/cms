<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="email_id_ctx"><?php echo __('Email ID (Ctx)'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input( 'email_id_ctx', $widget->email_id_ctx, array(
				'class' => 'form-control', 'id' => 'email_id_ctx'
			)); ?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="next_url"><?php echo __('Next page (URI)'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('next_url', $widget->get('next_url'), array(
				'class' => 'form-control', 'id' => 'next_url'
			)); ?>
		</div>
	</div>
</div>