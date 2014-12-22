<script type="text/javascript">
$(function() {
	$('select[name="search_field"]').on('change', function() {
		$('.search-input').prop('name', 'search['+$(this).val()+']');
	});
});
</script>
<div id="toolbar">
	<div class="form-search">
		<div class="input-group">
			<div class="input-group-btn">
				<?php echo Form::select('search_field', $datasource->headline()->searchable_fields(), 'header', array(
					'class' => 'form-control', 'style' => 'width: 150px'
				)); ?>
			</div>
			<input type="text" name="search[header]" class="form-control search-input" value="<?php echo $keyword; ?>" placeholder="<?php echo __('Search'); ?>">

			<div class="input-group-btn">
				<button class="btn btn-default"><?php echo UI::icon('search'); ?> <?php echo __('Search'); ?></button>
			</div>
		</div>
	</div>
</div>