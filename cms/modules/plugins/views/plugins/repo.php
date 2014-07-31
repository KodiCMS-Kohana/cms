<script id="plugin-item" type="text/template">

	<td class="plugin-name">
		<h5><%= name %> <% if (is_installed) { %> <strong>(<?php echo __('Installed'); ?>)</strong><% } %> <% if (is_new) { %> <strong>(<?php echo __('New plugin'); ?>)</strong><% } %></h5>
		<p class="muted"><%= description %></p>
			
		<div class="text-info">
			<p><strong><?php echo __('Install plugin'); ?>:</strong> <?php echo __('Download and copy files to directory'); ?> <code><%= plugin_path %></code></p>
			<p><strong><?php echo __('Install using git'); ?>:</strong> <code>git submodule add <%= description %> <%= plugin_path %></code></p>
		</div>
	</td>
	<td class="plugin-update">
		<%= last_update %>
	</td>
	<td class="plugin-url">
		<a href="<%= url %>" target="blank" class="btn btn-link"><?php echo __('Repository'); ?></a>
	</td>
	<td class="plugin-link">
		<a href="<%= archive %>" class="btn"><?php echo __('Download'); ?></a>
		<a href="<%= url %>/issues" target="blank" class="btn btn-mini btn-warning"><?php echo __('Report a bug'); ?></a>
	</td>
</script>

<div id="pluginsMap" class="widget widget-nopad">
	<div class="widget-header"></div>
	<div class="widget-content">
		<table class="table table-striped table-hover" id="PluginsList">
			<colgroup>
				<col />
				<col width="200px" />
				<col width="150px" />
				<col width="200px" />
		</colgroup>
			<thead>
				<tr>
					<th><?php echo __('Plugin name'); ?></th>
					<th><?php echo __('Last update'); ?></th>
					<th><?php echo __('URL'); ?></th>
					<th><?php echo __('Link'); ?></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>