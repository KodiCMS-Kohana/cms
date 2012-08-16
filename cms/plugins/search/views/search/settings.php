<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo __('Search settings'); ?></h1> 
</div>

<ul class="breadcrumb">
	<li><a href="<?php echo URL::site('plugins'); ?>"><?php echo __('Plugins'); ?></a> <span class="divider">/</span></li>
	<li class="active"><?php echo __('Search settings'); ?></li>
</ul>

<form class="form-horizontal" action="<?php echo URL::site('plugin/search/settings'); ?>" method="post">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="seach-query-key"><?php echo __( 'Search query key' ); ?></label>
			<div class="controls">
				
				<?php echo Form::input('setting[search_query_key]', Arr::get($settings, 'search_query_key', 'q'), array(
					'class' => 'input-xlarge', 'id' => 'seach-query-key'
				)); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="seach-query-key"><?php echo __( 'Search only in title' ); ?></label>
			<div class="controls">
				
				<label class="radio">
					<?php echo Form::radio('setting[search_only_title]', 'yes', Arr::get($settings, 'search_only_title') == 'yes', array(
						'class' => 'input-xlarge', 'id' => 'seach-query-key'
					)); ?>  <?php echo __( 'Yes' ); ?>
				</label>
				<label class="radio">
					<?php echo Form::radio('setting[search_only_title]', 'no', Arr::get($settings, 'search_only_title') == 'no', array(
						'class' => 'input-xlarge', 'id' => 'seach-query-key'
					)); ?>  <?php echo __( 'No' ); ?>
				</label>
			</div>
		</div>

		<div class="form-actions">
		<?php echo Form::button('submit', HTML::icon('ok') .' '. __('Save setting'), array(
			'class' => 'btn btn-large btn-success'
		)); ?>
		</div>
	</fieldset>
</form>