<?php echo Form::open(Route::get('email_controllers')->uri(array('controller' => 'types', 'action' => $action, 'id' => $type->id)), array(
	'class' => array(Form::HORIZONTAL, 'panel')
)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('General information'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group form-group-lg">
			<?php echo $type->label('name', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $type->field('name', array('class' => 'form-control')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<?php echo $type->label('code', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<?php if($action == 'add'): ?>
				<?php echo $type->field('code', array('class' => 'form-control slug', 'data-separator' => '_')); ?>
				<?php else: ?>
				<?php echo Form::input(NULL, $type->code, array('class' => 'form-control', 'readonly', 'disabled')); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Email type fields'); ?></span>
	</div>
	<div class="panel-body" id="type-fields">
		<?php echo View::factory('helper/rows', array(
			'field' => 'data',
			'data' => $type->data()
		)); ?>
	</div>
	
	<?php if($action == 'edit'): ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Linked email templates'); ?></span>
		
		<div class="panel-heading-controls">
			<?php if ( Acl::check('email_template.add')): ?>
			<?php echo UI::button(__('Add linked template'), array(
				'href' => Route::get('email_controllers')->uri(array(
					'controller' => 'templates', 'action' => 'add')) . '?email_type='.$type->id, 
				'icon' => UI::icon('plus'),
				'class' => 'btn-default'
			)); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php if(count($templates) > 0): ?>
	<ul class="list-group">
	<?php foreach($templates as $tpl): ?>
		<li class="list-group-item"><?php echo HTML::anchor(Route::get('email_controllers')->uri(array(
			'controller' => 'templates',
			'action' => 'edit',
			'id' => $tpl->id
		)), $tpl->subject); ?></li>
	<?php endforeach; ?>
	</ul>
	<?php endif; ?>
	<?php endif; ?>
	<div class="form-actions panel-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php Form::close(); ?>