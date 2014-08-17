<div id="toolbar" class="panel">
	<div class="panel-body">
		<?php echo Form::open(Request::initial(), array(
			'class' => 'form-search',
			'method' => Request::GET
		)); ?>
			<div class="input-group">
				<input type="text" name="keyword" class="form-control" value="<?php echo $keyword; ?>" placeholder="<?php echo __('Search'); ?>">
				
				<div class="input-group-btn">
					<button class="btn"><?php echo UI::icon('search'); ?> <?php echo __('Search'); ?></button>
				</div>
			</div>
		<?php echo Form::close(); ?>
	</div>	
</div>