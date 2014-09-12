<script type="text/javascript">
var DS_ID = <?php echo $datasource->id(); ?>;
var DS_TYPE = '<?php echo $datasource->type(); ?>';
</script>
<div class="mail-container-header">
	<?php echo $datasource->name; ?>
	
	<div class="btn-group pull-right">
		<?php if($datasource->has_access_edit())
		{
			echo UI::button(NULL, array(
				'href' => Datasource_Section::uri('edit', $datasource->id()),
				'icon' => UI::icon( 'wrench' ),
				'class' => 'btn btn-default',
				'title' => __('Edit'),
				'hotkeys' => 'ctrl+e'
			));
		}
		
		if($datasource->has_access_remove())
		{
			echo UI::button(NULL, array(
				'href' => Datasource_Section::uri('remove', $datasource->id()),
				'icon' => UI::icon( 'trash-o fa-inverse' ),
				'class' => 'btn btn-danger btn-confirm',
				'title' => __('Remove')
			));
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