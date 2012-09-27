<script>
	$(function() {
		$('#PluginsList .change-status').live('click', function(){
			var tr = $(this).parent().parent(),
				id = tr.attr('id'),
				status = $(this).data('status') ? 0 : 1;

			$.post(BASE_URL + '/plugins/status', {id: id, status: status}, function(request) {
				if(!request.status)
					return;

				tr.replaceWith(request.html)
			}, 'json');
		})
	})
</script>

<div class="page-header">
	<h1><?php echo __('Plugins'); ?></h1> 
</div>

<div id="pluginsMap" class="box map">
	
	<table class="table table-striped table-hover" id="PluginsList">
		<colgroup>
			<col />
			<col width="80px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Plugin name'); ?></th>
				<th><?php echo __('Version'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($plugins as $id => $plugin): ?>
				<?php $status = isset($loaded_plugins[$id]) ? 'activated' : 'deactivated'; ?>
				<?php
				echo View::factory( 'plugins/status/' . $status, array(
					'id' => $id,
					'plugin' => $plugin
				));
				?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div><!--/#pluginsMap-->