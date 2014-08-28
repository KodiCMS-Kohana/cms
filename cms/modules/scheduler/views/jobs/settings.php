<div class="panel-heading panel-toggler" data-icon="bolt">
	<span class="panel-title"><?php echo __('Job settings'); ?></span>
</div>
<div class="panel-body panel-spoiler">
	<div class="note note-warning">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('When using cron you need to add the following line to the crontab file:'); ?>
		<br /><br />
		<strong>* * * * * cd <?php echo DOCROOT;?>; php -f index.php --task=job:run &gt; /dev/null 2&gt;&amp;1</strong>
	</div>
				
	<div class="form-group">
		<label class="control-label col-sm-3"><?php echo __( 'Job agent' ); ?></label>
		<div class="col-sm-3">
			<?php echo Form::select('setting[job][agent]', Model_Job::agents(), (int) Config::get('job', 'agent')); ?>
		</div>
	</div>
</div>