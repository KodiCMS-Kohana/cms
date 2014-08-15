<script id="plugin-item" type="text/template">
	<td>
		<div class="row-fluid">
			<div class="span12">
				<h5 class="pull-left">
					<a href="<%= url %>" target="blank" class="lead" title="<?php echo __('Go to plugin homepage'); ?>"><%= name %></a>
					<% if (is_installed) { %> <strong>(<?php echo __('Installed'); ?>)</strong><% } %> <% if (is_new) { %> <strong>(<?php echo __('New plugin'); ?>)</strong><% } %>&nbsp;&nbsp;&nbsp;&nbsp;
					<i class="fa fa-star<% if (stars == 0) { %>-o<% } %>"></i> <%= stars %>&nbsp;&nbsp;&nbsp;&nbsp;
					<i class="fa-eye"></i> <%= watchers %>&nbsp;&nbsp;&nbsp;&nbsp;
				</h5>
				<div class="pull-right">
					<a href="<%= archive_url %>" class="btn"><?php echo __('Download'); ?></a>
					<a href="<%= url %>/issues" target="blank" class="btn btn-mini btn-warning"><?php echo __('Report a bug'); ?></a>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<% if (description) { %><p class="muted"><%= description %></p><% } %>
			
		<% if (!is_installed) { %>
		<div class="text-info">
			<p><strong><?php echo __('Install plugin'); ?>:</strong> <?php echo __('Download and copy files to directory'); ?> <code><%= plugin_path %></code></p>
			<p><strong><?php echo __('Install using git'); ?>:</strong> <code>git submodule add <%= clone_url %> <%= plugin_path %></code></p>
		</div>
		<% } %>
		<small class="muted"><strong><?php echo __('Last update'); ?>:</strong> <%= last_update %></small>
	</td>
</script>

<div id="pluginsMap" class="widget widget-nopad">
	<div class="panel-body">
		<table class="table table-striped table-hover" id="PluginsList">
			<thead>
				<tr>
					<th><?php echo __('Plugin name'); ?></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>