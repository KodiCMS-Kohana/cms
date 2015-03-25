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

<div class="panel-heading">
	<span class="panel-title"><?php echo __('Search in sources'); ?></span>
</div>
<div class="panel-body ">
	<div class="form-group">
		<label class="control-label col-md-3" for="sources"><?php echo __('Datasources'); ?></label>
		<div class="col-md-3">
			<?php echo Form::select('sources[]', $widget->sources(), (array) $widget->sources, array(
				'id' => 'sources', 'class' => 'form-control'
			)); ?>
		</div>
	</div>
</div>

<div class="panel-heading">
	<span class="panel-title"><?php echo __('Datasource links'); ?></span>
</div>
<div class="panel-body ">
	<?php foreach ($widget->sources() as $id => $header): if (!in_array($id, $widget->sources)) continue; ?>
	<div class="form-group">
		<label class="control-label col-md-3" for="source<?php echo $id; ?>"><?php echo $header; ?></label>
		<div class="col-md-3">
			<?php echo Form::input('source_hrefs['.$id.']', Arr::get($widget->source_hrefs, $id), array(
				'id' => 'source' . $id, 'class' => 'form-control'
			)); ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>

