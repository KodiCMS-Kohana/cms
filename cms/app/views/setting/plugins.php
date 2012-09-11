<div class="page-header">
	<h1><?php echo __('Plugins'); ?></h1> 
</div>

<div id="pluginsMap" class="box map">
	
	<div id="pluginsMapActions" class="box-actions">
		
	</div>
	
	<table class="table_list" id="PluginsList">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="80px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Plugin name'); ?></th>
				<th><?php echo __('Author'); ?></th>
				<th><?php echo __('Version'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($plugins as $plugin): ?>
			<tr class="item">
				<td class="title">
					<em><?php echo __($plugin->title); ?></em>
					<i><?php echo $plugin->description; ?></i>
				</td>
				<td class="author"><?php echo(isset($plugin->author) ? $plugin->author : __('n/a')); ?></td>
				<td class="version"><?php echo $plugin->version; ?></td>
				<td class="actions">
					<?php 
					if( isset($loaded_plugins[$plugin->id]) && Plugins::hasSettingsPage($plugin->id) )
					{
						echo UI::button(NULL, array(
							'href' => $plugin->id.'/settings', 'icon' => UI::icon('cog'), 
							'class' => 'btn btn-mini'
						));
					}
						
					if (isset($loaded_plugins[$plugin->id]) && Plugins::hasDocumentationPage($plugin->id) )
					{
						echo UI::button(NULL, array(
							'href' => $plugin->id.'/documentation', 'icon' => UI::icon('book'), 
							'class' => 'btn btn-mini'
						));
					}
					
					if ( Plugins::isEnabled($plugin->id) )
					{
						echo UI::button(NULL, array(
							'href' => 'plugins/deactivate/'.$plugin->id, 'icon' => UI::icon('stop'), 
							'class' => 'btn btn-mini'
						));
					}
					else
					{
						echo UI::button(NULL, array(
							'href' => 'plugins/activate/'.$plugin->id, 'icon' => UI::icon('play'), 
							'class' => 'btn btn-mini'
						));
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div><!--/#pluginsMap-->