<?php if (!empty($fields)): ?>
<div class="btn-group fields-control pull-right">		
	<div class="btn-group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<?php echo __('Fields in headline'); ?> <i class="fa fa-caret-down"></i>
		</button>
		<ul class="dropdown-menu padding-sm" role="menu">
			<?php foreach ($fields as $field): ?>
			<li>
				<label class="checkbox-inline">
					<?php echo Form::checkbox('in_headline', $field->id, $field->in_headline); ?> 
					<?php echo $field->header; ?>
				</label>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<script type="text/javascript">
$(function() {
	$('.fields-control input[type="checkbox"]').on('change', function() {
		var $self = $(this);
		
		if($self.checked()) {
			Api.post('/datasource/hybrid-field.headline', {id: $self.val()}, function(response) {
				update_headline();
			});
		} else {
			Api.delete('/datasource/hybrid-field.headline', {id: $self.val()}, function(response) {
				update_headline();
			});
		}
	});
});
</script>
<?php endif; ?>