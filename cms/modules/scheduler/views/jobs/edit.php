<?php echo Form::open(Route::get('backend')->uri(array('controller' => 'jobs', 'action' => $action, 'id' => $job->id)), array(
	'class' => array(Form::HORIZONTAL, 'panel')
)); ?>

	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('General information'); ?></span>
		
		<div class="panel-heading-controls">
		<?php if ($job->loaded() AND Acl::check('jobs.run')): ?>
		<?php echo UI::button(__('Run job'), array(
			'href' => Route::get('backend')->uri(array('controller' => 'jobs', 'action' => 'run', 'id' => $job->id)), 
			'icon' => UI::icon('bolt'),
			'class' => 'btn-danger btn-sm'
		)); ?>
		<?php endif; ?>
		</div>
	</div>
	<div class="panel-body">
		<div class="form-group form-group-lg">
			<?php echo $job->label('name', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $job->field('name', array('class' => 'form-control')); ?>
			</div>
		</div>
	
		<div class="form-group">
			<?php echo $job->label('job', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $job->field('job'); ?>
			</div>
		</div>
	</div>
	
	<div class="panel-heading" data-icon="clock-o">
		<span class="panel-title"><?php echo __('Runing options'); ?></span>
	</div>
	<div class="panel-body">
		<div class='well form-inline'>
			<?php echo $job->label('date_start'); ?> <?php echo $job->field('date_start', array('class' => 'datetimepicker form-control')); ?>
			&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
			<?php echo $job->label('date_end'); ?> <?php echo $job->field('date_end', array('class' => 'datetimepicker form-control')); ?>
		</div>
		<div class="form-group">
			<?php echo $job->label('interval', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9 form-inline">
				<?php echo $job->field('interval', array('class' => 'form-control col-sm-auto')); ?>
				
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
			<div class="col-md-offset-3 col-md-9">
				<?php echo __('Or'); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $job->label('crontime', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9 form-inline">
				<?php echo $job->field('crontime', array('class' => 'form-control')); ?>
				<span class="help-inline"><?php echo HTML::anchor('http://ru.wikipedia.org/wiki/Cron', __('Crontab help'), array(
					'target' => 'blank'
				)); ?></span>
				
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

<?php Form::close(); ?>

<?php if(!empty($history)): ?>
<?php echo $history; ?>
<?php endif; ?>
