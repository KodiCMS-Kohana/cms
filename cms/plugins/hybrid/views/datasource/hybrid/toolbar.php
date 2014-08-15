<div id="toolbar" class="widget">
	<div class="panel-body">
		<?php echo Form::open(Request::initial(), array(
			'class' => 'form-search',
			'method' => Request::GET
		)); ?>
			<div class="input-append">
				<input type="text" name="keyword" class="input-large search-query" value="<?php echo $keyword; ?>" placeholder="<?php echo __('Search'); ?>">
				<button class="btn"><?php echo UI::icon('search'); ?> <?php echo __('Search'); ?></button>	
			</div>
		<?php echo Form::close(); ?>
	</div>	
</div>
<br />