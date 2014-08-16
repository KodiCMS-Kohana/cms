<h1><?php echo $title; ?></h1>

<div class="widget">
	<div class="panel-body">
		<p class="lead"><?php echo __("There doesn't seem to be a :config file. I need this before we can get started.", array(':config' => CFGFATH)); ?></p>
		<hr />
		<?php echo HTML::anchor(Route::get('install')->uri(array('action' => 'index')), __('Create a Configuration File'), array(
			'class' => 'btn btn-lg btn-primary pull-right'
		)); ?>
	</div>
</div>