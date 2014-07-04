<br />
<h3 class="page-header"><?php echo $header; ?> <small>(<?php echo __('Fetched section'); ?>)</small></h3>

<?php echo HTML::anchor(Route::get('datasources')->uri(array(
	'controller' => 'document',
	'directory' => 'hybrid',
	'action' => 'view'
)) . URL::query(array(
	'ds_id' => $datasource->id, 'id' => $document->id
)), __('Hybrid document'), array('class' => 'btn pull-right')); ?>

<dl class="dl-horizontal">
<?php foreach($fields as $field): ?>
	<dt><?php echo $field->header; ?></dt>
	<dd><?php echo $field->fetch_headline_value($document->get($field->name)); ?></dd>
<?php endforeach; ?>
</dl>