<?php if(!$profile_ds_id ): ?>
<div class="alert alert-warning alert-dark">
	<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('You need select hybrid section'); ?>
</div>
<?php else: ?>
<div class="panel-body">
	<div class="form-group">
		<label class="control-label col-md-3" for="profile_id_ctx"><?php echo __('Profile ID (Ctx)'); ?></label>
		<div class="col-md-4">
			<?php echo Form::input('profile_id_ctx', $widget->get('profile_id_ctx'), array(
				'class' => 'form-control', 'id' => 'profile_id_ctx'
			)); ?>
		</div>
	</div>
</div>

<?php echo View::factory('widgets/backend/blocks/fields', array(
	'widget' => $widget
)); ?>
<?php endif; ?>