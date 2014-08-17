<?php echo Form::open(Route::get('backend')->uri(array('controller' => 'scheduler', 'action' => $action, 'id' => $job->id)), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>

<?php echo Form::hidden('token', Security::token()); ?>
<div class="widget">
	<div class="panel-heading">
		<h4><?php echo __('General information'); ?></h4>
		
		<?php if($job->loaded() AND  Acl::check( 'scheduler.run')): ?>
		<?php echo UI::button(__('Run job'), array(
			'href' => Route::get('backend')->uri(array('controller' => 'scheduler', 'action' => 'run', 'id' => $job->id)), 'icon' => UI::icon('play')
		)); ?>
		<?php endif; ?>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<?php echo $job->label('name', array('class' => 'control-label title')); ?>
			<div class="controls">
				<?php echo $job->field('name', array('class' => 'input-title input-block-level')); ?>
			</div>
		</div>
	
		<div class="form-group">
			<?php echo $job->label('job', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $job->field('job'); ?>
			</div>
		</div>
	</div>
	
	<div class="panel-heading">
		<h4><?php echo __('Runing options'); ?></h4>
	</div>
	<div class="panel-body">
		<div class='well form-inline'>
			<?php echo $job->label('date_start'); ?> <?php echo $job->field('date_start', array('class' => 'datetimepicker input-medium')); ?> -
			<?php echo $job->label('date_end'); ?> <?php echo $job->field('date_end', array('class' => 'datetimepicker input-medium')); ?>
		</div>
		<div class="form-group">
			<?php echo $job->label('interval', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $job->field('interval', array('class' => 'input-medium')); ?>
				
				<span class="flags">
					<span class="label" data-value="<?php echo Date::MINUTE; ?>"><?php echo __('Minute'); ?></span> 
					<span class="label" data-value="<?php echo Date::HOUR; ?>"><?php echo __('Hour'); ?></span>
					<span class="label" data-value="<?php echo Date::DAY; ?>"><?php echo __('Day'); ?></span>
					<span class="label" data-value="<?php echo Date::WEEK; ?>"><?php echo __('Week'); ?></span>
					<span class="label" data-value="<?php echo Date::MONTH; ?>"><?php echo __('Month'); ?></span>
					<span class="label" data-value="<?php echo Date::YEAR; ?>"><?php echo __('Year'); ?></span>
				</span>
			</div>
		</div>
		<div class="form-group">
			<div class="controls">
				<?php echo __('Or'); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $job->label('crontime', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $job->field('crontime', array('class' => 'input-large')); ?>
				<p class="help-inline"><?php echo HTML::anchor('http://ru.wikipedia.org/wiki/Cron', __('Crontab help'), array(
					'target' => 'blank'
				)); ?></p>
				
				<pre style="font-size: 16px; background: none; border: none;">
* * * * *
| | | | --- <?php echo __('Day of week (0 - 6) (0 to 6 are Sunday to Saturday, or use names; 7 is Sunday, the same as 0)'); ?> 
| | | ----- <?php echo __('Month (1 - 12)'); ?> 
| | ------- <?php echo __('Day of month (1 - 31)'); ?> 
| --------- <?php echo __('Hour (0 - 23)'); ?> 
----------- <?php echo __('Min (0 - 59)'); ?> 
				</pre>
			</div>
		</div>
	</div>
	<div class="form-actions panel-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
</div>
<?php Form::close(); ?>

<?php if(!empty($history)): ?>
<?php echo $history; ?>
<?php endif; ?>
