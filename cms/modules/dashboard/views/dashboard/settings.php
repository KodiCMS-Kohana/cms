<div class="panel-heading" data-icon="dashboard">
	<span class="panel-title" id="dashboard-settings"><?php echo __('Dashboard settings'); ?></span>
</div>
<div class="panel-body">
	<?php if (ACL::check('dashboard.empty')): ?>
	<div class="well">
		<?php echo UI::button(__('Empty dashboard'), array(
			'icon' => UI::icon('trash-o fa-lg'),
			'class' => 'btn-warning',
			'data-api-url' => 'dashboard',
			'data-method' => Request::DELETE
		)); ?>
	</div>
	<?php endif; ?>
</div>