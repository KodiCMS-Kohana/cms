<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="search_key"><?php echo __('Search key ($_GET)'); ?></label>
		<div class="col-md-3">
			<?php echo Form::input('search_key', $widget->search_key, array(
				'id' => 'search_key', 'class' => 'form-control'
			)); ?>
		</div>
	</div>
</div>