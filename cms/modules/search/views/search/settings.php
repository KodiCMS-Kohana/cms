<div class="panel-heading" data-icon="search">
	<span class="panel-title" id="cache-settings"><?php echo __('Search settings'); ?></span>
</div>
<div class="panel-body">
	<div class="well">
		<?php echo UI::button(__('Update search index'), array(
			'icon' => UI::icon( 'refresh fa-lg' ),
			'class' => 'btn-warning',
			'data-api-url' => 'search.update_index'
		)); ?>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3" for="settingFullTextSearch"><?php echo __('Enable full text search'); ?></label>
		<div class="col-md-2">
			<?php echo Form::select('setting[search][full_text_search]', array(
				1 => __('Yes'), 
				0 => __('No')
			), (int) Config::get('search', 'full_text_search')); ?>
		</div>
	</div>
</div>