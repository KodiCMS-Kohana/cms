<?php if (count($types) > 0): ?>
<?php foreach ($types as $key => $data): ?>
<div class="col-sm-6">
	<div class="panel">
		<div class="panel-body padding-sm">
			<button class="btn btn-default popup-btn pull-right" data-type="<?php echo $key; ?>"><?php echo __('Install widget'); ?></button>
			<h4 <?php if (!empty($data['icon'])): ?>data-icon="<?php echo $data['icon']; ?> fa-lg"<?php endif; ?>><?php echo Arr::get($data, 'title'); ?></h4>
			<?php if (!empty($data['description'])): ?>
			<p class="text-muted"><?php echo $data['description']; ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="panel">
	<div class="panel-body">
		<h2><?php echo __('No widgets'); ?></h2>
	</div>
</div>
<?php endif; ?>
<script type="text/javascript">
$(function() {
	cms.ui.init('icon');
});
</script>