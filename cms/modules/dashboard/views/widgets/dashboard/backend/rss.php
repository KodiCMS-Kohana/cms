<div class="panel-heading">
	<span class="panel-title"><?php echo __('Properties'); ?></span>
</div>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="rss_url"><?php echo __('RSS url'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('rss_url', $widget->rss_url, array(
				'class' => 'form-control', 'id' => 'rss_url'
			)); ?>
		</div>
	</div>
	
	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="limit"><?php echo __('Number of items'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('limit', $widget->limit, array(
				'class' => 'form-control', 'id' => 'limit', 'size' => 3
			)); ?>
		</div>
	</div>
	
	<div class="form-group form-inline">
		<label class="control-label col-md-3" for="height"><?php echo __('Widget height'); ?></label>
		<div class="col-md-9">
			<?php echo Form::input('height', $widget->height, array(
				'class' => 'form-control', 'id' => 'height', 'size' => 3
			)); ?>
		</div>
	</div>
</div>