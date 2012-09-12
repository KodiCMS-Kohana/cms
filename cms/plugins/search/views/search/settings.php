<div class="page-header">
	<h1><?php echo __('Search settings'); ?></h1> 
</div>

<?php echo Form::open('search/settings', array(
	'class' => 'form-horizontal', 'method' => 'post'
)); ?>

	<?php echo Form::hidden('token', Security::token()); ?>

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
	<?php echo UI::button(__('Save setting'), array(
		'class' => 'btn btn-large btn-success', 'icon' => UI::icon('ok'),
		'name' => 'submit'
	)); ?>
	</div>
<?php echo Form::close(); ?>