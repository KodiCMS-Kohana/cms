<script type="text/javascript">
var DS_ID = <?php echo $datasource->id(); ?>;
var DS_TYPE = '<?php echo $datasource->type(); ?>';
</script>
<div class="mail-container-header">
	<?php echo $datasource->name; ?>
	
	<div class="btn-group pull-right">
		<?php if($datasource->has_access('section.edit'))
		{
			$attributes =  array(
				'href' => Route::get('datasources')->uri(array(
					'controller' => 'section',
					'directory' => 'datasources',
					'action' => 'edit',
					'id' => $datasource->id()
				)),
				'icon' => UI::icon( 'wrench' ),
				'class' => 'btn btn-default'
			);
			
			if($selected == 'active')
			{
				$attributes['hotkeys'] = 'ctrl+e';
			}
			
			echo UI::button(NULL, $attributes);
		}
		
		if($datasource->has_access('section.remove'))
		{
			$attributes =  array(
				'href' => Route::get('datasources')->uri(array(
					'controller' => 'section',
					'directory' => 'datasources',
					'action' => 'remove',
					'id' => $datasource->id()
				)),
				'icon' => UI::icon( 'trash-o fa-inverse' ),
				'class' => 'btn btn-danger btn-confirm'
			);
			echo UI::button(NULL, $attributes);
		}
		?>
	</div>
</div>
<div class="mail-controls clearfix headline-actions">
	<?php echo View::factory('datasource/section/actions'); ?>
</div>
<div class="mail-list headline">
	<?php echo $headline; ?>
</div>