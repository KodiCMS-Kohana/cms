<?php echo $toolbar; ?>

<div id="headline" class="widget">
	<div class="tablenav form-inline widget-header page-actions">
		<?php try 
		{
			echo View::factory('datasource/'.$ds_type.'/actions');
		}
		catch (Exception $exc)
		{
			echo View::factory('datasource/section/actions', array());
		} ?>
		<div class="clearfix"></div>
	</div>
	
	<div class="widget-content widget-nopad">
		<?php echo $headline; ?>
	</div>
</div>