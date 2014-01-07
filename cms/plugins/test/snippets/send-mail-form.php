<?php 
	$errors = Flash::get('errors', array());
	$messages = Flash::get('messages', array());
	
	$status = Arr::get($_GET, 'status');
?>

<?php if(!empty($errors)): ?>
<div class="alert">
	<h3><?php echo __('Errors'); ?></h3>
	<ul>
	<?php foreach($errors as $error): ?>
		<li><?php echo $error['error']; ?></li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<?php if($status == 'ok'):?>
<div class="alert alert-success">
	<h3><?php echo __('Message send'); ?></h3>
</div>
<?php endif; ?>
<?php echo Form::open('contacts/send', array('class' => 'form-horizontal')); ?>
	<div class="control-group">
		<label class="control-label" for="inputSubject"><?php echo __('Subject'); ?></label>
		<div class="controls">
			<input type="text" name="subject" id="inputSubject" placeholder="<?php echo __('Subject'); ?>" value="<?php echo Arr::get($messages, 'subject'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputEmail"><?php echo __('Email'); ?></label>
		<div class="controls">
			<input type="text" name="email" id="inputEmail" placeholder="<?php echo __('Email'); ?>" value="<?php echo Arr::get($messages, 'email'); ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputText"><?php echo __('Text'); ?></label>
		<div class="controls">
			<textarea name="text" id="inputText"><?php echo Arr::get($messages, 'text'); ?></textarea>
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn"><?php echo __('Send'); ?></button>
    </div>
<?php echo Form::close(); ?>