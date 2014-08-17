<?php echo $toolbar; ?>

<div id="headline">
	<div class="panel">
		<div class="page-actions">
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
		
		<?php echo $headline; ?>
	</div>
</div>