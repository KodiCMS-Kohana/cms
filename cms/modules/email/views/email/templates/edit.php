<script type="text/javascript">
	var EMAIL_TEMPLATE_ID = <?php echo (int) $template->id; ?>;
	
	$(function() {
		$('#email_template_email_type').on('change', function() {
			show_options($(this).val());
		});
		
		var message_type = $(':radio[name="message_type"]:checked').val();
		$(':radio[name="message_type"]').on('change', function() {
			message_type = $(this).val();
			change_message_redator(message_type)
		});
		
		change_message_redator(message_type);

		function change_message_redator(type) {
			if(type == '<?php echo Model_Email_Template::TYPE_HTML; ?>')
				cms.filters.switchOn( 'email_template_message', 'redactor' );
			else
				cms.filters.switchOff('email_template_message');
		}
		
		var activeInput;
		$(':input').not(':radio').not('select').add('.redactor_editor').on('focus', function() {
			activeInput = $(this);
		})
		
		$('#field_description').on('click', 'a', function() {
			var curInput = activeInput;

			if(!activeInput) return false;

			if(curInput.hasClass('redactor_editor') && message_type == '<?php echo Model_Email_Template::TYPE_HTML; ?>') {
				cms.filters.exec('email_template_message', 'insert', $(this).text());
			} else {
				var cursorPos = curInput.prop('selectionStart');
				var v = curInput.val();
				var textBefore = v.substring(0,  cursorPos );
				var textAfter  = v.substring( cursorPos, v.length );
				curInput.val( textBefore+ $(this).text() +textAfter );
			}
			
			return false;
		});

		show_options($('#email_template_email_type').val());
		function show_options(id) {
			Api.get('email-types.options', {uid: id}, function(resp) {
				var cont = $('#field_description .controls').empty();
				var ul = $('<ul class="unstyled" />').appendTo(cont);
				if(resp.response) {
					for(field in resp.response) {
						$('<li><a href="#">{'+field+'}</a> - ' + resp.response[field] + '</li>').appendTo(ul);
					}
				}
			})
		}
	});
	
</script>

<?php echo Form::open(Route::get('email_controllers')->uri(array('controller' => 'templates', 'action' => $action, 'id' => $template->id)), array(
	'class' => Bootstrap_Form::HORIZONTAL
)); ?>

<?php echo Form::hidden('token', Security::token()); ?>
<div class="widget">
	<div class="widget-header">
		<h3><?php echo __('General information'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="control-group">
			<?php echo $template->label('status', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $template->field('status'); ?>
			</div>
		</div>
		<div class="control-group">
			<?php echo $template->label('use_queue', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $template->field('use_queue'); ?>
			</div>
		</div>
		<div class="control-group">
			<?php echo $template->label('email_type', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $template->field('email_type'); ?>
				
				<?php if ( Acl::check( 'email_type.add')): ?>
				<?php echo UI::button(__('Add email type'), array(
					'href' => Route::get( 'email_controllers')->uri(array('controller' => 'types', 'action' => 'add')), 'icon' => UI::icon('plus'),
					'class' => 'btn btn-primary'
				)); ?>
				<?php endif; ?>
			</div>
		</div>
		<hr />
		
		<div class="control-group">
			<?php echo $template->label('subject', array('class' => 'control-label title')); ?>
			<div class="controls">
				<?php echo $template->field('subject', array('class' => 'input-title input-block-level')); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo $template->label('email_from', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $template->field('email_from'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo $template->label('email_to', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $template->field('email_to'); ?>
			</div>
		</div>
	</div>
	<div class="widget-header">
		<h3><?php echo __('Email message'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="control-group">
			<?php echo $template->label('message_type', array('class' => 'control-label')); ?>
			<div class="controls">
				<label class="radio inline">
					<?php echo Form::radio('message_type', Model_Email_Template::TYPE_TEXT, $template->message_type == Model_Email_Template::TYPE_TEXT); ?> <?php echo __('Plain text'); ?>
				</label>
				<label class="radio inline">
					<?php echo Form::radio('message_type', Model_Email_Template::TYPE_HTML, $template->message_type == Model_Email_Template::TYPE_HTML); ?> <?php echo __('HTML'); ?>
				</label>
			</div>
		</div>
		
		<div class="control-group">
			<?php echo $template->label('message', array('class' => 'control-label')); ?>
			<div class="controls">
				<?php echo $template->field('message', array('class' => 'input-block-level')); ?>
			</div>
		</div>
		
		<div class="control-group" id="field_description"><div class="controls"></div></div>
		
		<div class="control-group">
			<div class="controls">
				<div class="alert alert-warning">
					<?php echo UI::icon('lightbulb-o'); ?> <?php echo __('A collection of patterns & modules for responsive emails :link', array(
						':link' => HTML::anchor('http://responsiveemailpatterns.com/', NULL, array(
							'target' => 'blank'
						))
					)); ?>
				</div>
			</div>		
		</div>
	</div>
	<div class="form-actions widget-footer">
		<?php echo UI::actions($page_name); ?>
	</div>
</div>
<?php Form::close(); ?>