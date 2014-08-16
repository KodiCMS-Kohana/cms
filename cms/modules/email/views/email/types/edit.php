<script type="text/javascript">
	var EMAIL_TYPE_ID = <?php echo (int) $type->id; ?>;
</script>

<?php echo Form::open(Route::get('email_controllers')->uri(array('controller' => 'types', 'action' => $action, 'id' => $type->id)), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>

<?php echo Form::hidden('token', Security::token()); ?>

<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('General information'); ?></h3>
	</div>
	<div class="panel-body">
		
		<div class="form-group">
			<?php echo $type->label('name', array('class' => 'control-label title')); ?>
			<div class="controls">
				<?php echo $type->field('name', array('class' => 'input-title input-block-level')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<?php echo $type->label('code', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php if($action == 'add'): ?>
				<?php echo $type->field('code', array('class' => 'slug', 'data-separator' => '_')); ?>
				<?php else: ?>
				<span class="input-xlarge uneditable-input"><?php echo $type->code; ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="widget-header">
		<h3><?php echo __('Email type fields'); ?></h3>
	</div>
	<div class="panel-body" id="type-fields">
		<?php echo View::factory('helper/rows', array(
			'field' => 'data',
			'data' => $type->data()
		)); ?>
	</div>
	
	<?php if($action == 'edit'): ?>
	<div class="widget-header">
		<h3><?php echo __('Linked email templates'); ?></h3>
	</div>
	<div class="panel-body">
		<?php if(count($templates) > 0): ?>
		<ul class="unstyled">
		<?php foreach($templates as $tpl): ?>
			<li><?php echo HTML::anchor(Route::get('email_controllers')->uri(array(
				'controller' => 'templates',
				'action' => 'edit',
				'id' => $tpl->id
			)), $tpl->subject); ?></li>
		<?php endforeach; ?>
		</ul>
		<hr />
		<?php endif; ?>
		
		<?php if ( Acl::check('email_template.add')): ?>
		<?php echo UI::button(__('Add linked template'), array(
			'href' => Route::get('email_controllers')->uri(array('controller' => 'templates', 'action' => 'add')) . '?email_type='.$type->id, 'icon' => UI::icon('plus')
		)); ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<div class="form-actions panel-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
</div>
<?php Form::close(); ?>