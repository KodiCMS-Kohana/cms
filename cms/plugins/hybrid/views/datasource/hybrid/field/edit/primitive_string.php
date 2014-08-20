
<div class="form-group">
	<div class="col-md-9 col-md-offset-3">
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('use_filemanager', 1, $field->use_filemanager == 1, array('id' => 'use_filemanager' )); ?> <?php echo __('Use filemanager to get data'); ?>
			</label>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3" for="primitive_default"><?php echo __( 'Default value' ); ?></label>
	<div class="col-md-9">
		<?php echo Form::input( 'default', $field->default, array(
			'class' => 'form-control', 'id' => 'primitive_default'
		)); ?>
	</div>
</div>

<hr />

<div class="form-group form-inline">
	<label class="control-label col-md-3" for="length"><?php echo __('Field length'); ?></label>
	<div class="col-md-2">
		<?php echo Form::input('length', $field->length, array(
			'class' => 'form-control', 'id' => 'length', 'size' => 3, 'maxlength' => 3
		)); ?>
	</div>
</div>

<hr />

<div class="form-group">
	<label class="control-label col-md-3" for="regexp"><?php echo __('Field validation'); ?></label>
	<div class="col-md-9">
		<?php  echo Form::input('regexp', $field->regexp, array(
			'class' => 'form-control', 'id' => 'regexp'
		)); ?>
		<br />
		<span class="flags">
			<span class="label" data-value="url"><?php echo __('URL'); ?></span>
			<span class="label" data-value="phone"><?php echo __('Phone number'); ?></span>
			<span class="label" data-value="email"><?php echo __('Email'); ?></span>
			<span class="label" data-value="email_domain"><?php echo __('Email domain'); ?></span>
			<span class="label" data-value="ip"><?php echo __('IP'); ?></span>
			<span class="label" data-value="credit_card"><?php echo __('Credit card'); ?></span>
			<span class="label" data-value="date"><?php echo __('Date'); ?></span>
			<span class="label" data-value="alpha"><?php echo __('Alpha'); ?></span>
			<span class="label" data-value="alpha_dash"><?php echo __('Alpha and hyphens'); ?></span>
			<span class="label" data-value="alpha_numeric"><?php echo __('Alpha and numbers'); ?></span>
			<span class="label" data-value="digit"><?php echo __('Integer digit'); ?></span>
			<span class="label" data-value="decimal"><?php echo __('Decimal'); ?></span>
			<span class="label" data-value="numeric"><?php echo __('Numeric'); ?></span>
			<span class="label" data-value="color"><?php echo __('Color'); ?></span>
		</span>

		<p class="help-block"><?php echo __('Regular expression or Valid class method :link', array(
			':link' => HTML::anchor( 'http://kohanaframework.org/3.3/guide/kohana/security/validation#provided-rules', NULL, array(
				'target' => 'blank'
			))
		)); ?></p>
	</div>
</div>