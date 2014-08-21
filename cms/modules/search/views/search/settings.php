<div class="panel-heading" data-icon="search">
	<span class="panel-title" id="cache-settings"><?php echo __('Search settings'); ?></span>
</div>
<div class="panel-body">
	<div class="well">
		<?php echo UI::button(__('Update search index'), array(
			'icon' => UI::icon( 'refresh fa-lg' ),
			'class' => 'btn btn-warning',
			'data-api-url' => 'search.update_index'
		)); ?>
	</div>
</div>