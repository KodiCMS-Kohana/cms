<?php echo Form::open(Route::get('email_controllers')->uri(array('controller' => 'templates', 'action' => $action, 'id' => $template->id)), array(
	'class' => array(Form::HORIZONTAL, 'panel')
)); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="panel-heading">
		<span class="panel-title"><?php echo __('General information'); ?></span>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<?php echo $template->label('status', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-3">
				<?php echo $template->field('status'); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $template->label('use_queue', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-3">
				<?php echo $template->field('use_queue'); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $template->label('email_type', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-6">
				<div class="input-group">
				<?php echo $template->field('email_type'); ?>
				
				<?php if ( Acl::check( 'email_type.add')): ?>
				<div class="input-group-btn">
					<?php echo UI::button(__('Add email type'), array(
						'href' => Route::get( 'email_controllers')->uri(array('controller' => 'types', 'action' => 'add')), 'icon' => UI::icon('plus'),
						'class' => 'btn-primary'
					)); ?>
				</div>
				<?php endif; ?>
				</div>
			</div>
		</div>
		<hr />
		
		<div class="form-group form-group-lg">
			<?php echo $template->label('subject', array('class' => 'control-label col-md-3 title')); ?>
			<div class="col-md-9">
				<?php echo $template->field('subject', array('class' => 'form-control')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<?php echo $template->label('email_from', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-3">
				<?php echo $template->field('email_from', array('class' => 'form-control')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<?php echo $template->label('email_to', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-3">
				<?php echo $template->field('email_to', array('class' => 'form-control')); ?>
			</div>
		</div>
	</div>

	<div class="panel-heading">
		<span class="panel-title"><?php echo __('Email message'); ?></span>
	</div>

	<div class="note note-info no-margin-vr">
		<?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('A collection of patterns & modules for responsive emails :link', array(
			':link' => HTML::anchor('http://responsiveemailpatterns.com/', NULL, array(
				'target' => 'blank'
			))
		)); ?>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<?php echo $template->label('message_type', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<label class="radio-inline">
					<?php echo Form::radio('message_type', Model_Email_Template::TYPE_TEXT, $template->message_type == Model_Email_Template::TYPE_TEXT); ?> <?php echo __('Plain text'); ?>
				</label>
				<label class="radio-inline">
					<?php echo Form::radio('message_type', Model_Email_Template::TYPE_HTML, $template->message_type == Model_Email_Template::TYPE_HTML); ?> <?php echo __('HTML'); ?>
				</label>
			</div>
		</div>
		
		<div class="form-group">
			<?php echo $template->label('message', array('class' => 'control-label col-md-3')); ?>
			<div class="col-md-9">
				<?php echo $template->field('message', array('class' => 'form-control')); ?>
			</div>
		</div>
		
		<div class="form-group" id="field_description"><div class="col-md-offset-3 col-md-9"></div></div>
	</div>
	<div class="form-actions panel-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php Form::close(); ?>