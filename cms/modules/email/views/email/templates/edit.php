<script type="text/javascript">
	var EMAIL_TEMPLATE_ID = <?php echo (int) $template->id; ?>;
	
	$('#email_types').on('change', function() {
		show_options($(this).val());
	});

	function show_options(id) {
		console.log(id);
		Api.get('email-types.options', {uid: id}, function(resp) {
		
		})
	}
</script>

<?php echo Form::open(Route::url('email_controllers', array('controller' => 'templates', 'action' => $action, 'id' => $template->id)), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>

<?php echo Form::hidden('token', Security::token()); ?>
<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('General information'); ?></h3>
	</div>
	<div class="widget-content">
		
		<div class="control-group">
			<label class="control-label" for="status"><?php echo __( 'Email status' ); ?></label>
			<div class="controls">
				<?php
				echo Form::select( 'status', array(Model_Email_Template::ACTIVE => __('Active'), Model_Email_Template::INACTIVE => __('Inactive')), (bool) $template->status, array(
					'id' => 'status'
				) );
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="email_type"><?php echo __( 'Email type' ); ?></label>
			<div class="controls">
				<?php
				echo Form::select( 'email_type', $types, $template->email_type, array(
					'id' => 'email_types'
				) );
				?>
			</div>
		</div>
		<hr />
		
		<div class="control-group">
			<label class="control-label title" for="subject"><?php echo __( 'Email subject' ); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'subject', $template->subject, array(
					'class' => 'input-title input-block-level', 'id' => 'subject'
				) );
				?>
			</div>
		</div>
		
		<?php
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Input::factory(array(
					'name' => 'email_from', 'value' => $template->email_from
				))
				->label(__('Email from'))
			));
			
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Input::factory(array(
					'name' => 'email_to', 'value' => $template->email_to
				))
				->label(__('Email to'))
			));
		?>
		<hr />
		<div class="control-group">
			<label class="control-label"><?php echo __( 'Message type' ); ?></label>
			<div class="controls">
				<label class="radio inline">
					<?php echo Form::radio('message_type', Model_Email_Template::TYPE_TEXT, $template->message_type == Model_Email_Template::TYPE_TEXT); ?> <?php echo __('Plain text'); ?>
				</label>
				<label class="radio inline">
					<?php echo Form::radio('message_type', Model_Email_Template::TYPE_HTML, $template->message_type == Model_Email_Template::TYPE_HTML); ?> <?php echo __('HTML'); ?>
				</label>
			</div>
		</div>
		
		<?php
			echo Bootstrap_Form_Element_Control_Group::factory(array(
				'element' => Bootstrap_Form_Element_Textarea::factory(array(
					'name' => 'message', 'body' => $template->message
				))
				->attributes('class', 'input-block-level')
				->label(__('Email message'))
			));
		?>
		
		<div class="control-group" id="field_description">
			<div class="controls">
				
			</div>
		</div>
		
	</div>
	<div class="form-actions widget-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
</div>
<?php Form::close(); ?>