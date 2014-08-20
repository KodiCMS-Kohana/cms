<script>$(function(){ cms.filters.switchOn( '<?php echo $field->name; ?>', '<?php echo $field->filter; ?>', {height: 200}) });</script>

<div class="form-group">
	<label class="control-label col-md-3"><?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?></label>
	<div class="col-md-9">
		<?php echo Form::textarea( $field->name, $value, array(
			'class' => 'form-control', 'id' => $field->name, 'data-height' => '265'
		) ); ?>
		
		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>