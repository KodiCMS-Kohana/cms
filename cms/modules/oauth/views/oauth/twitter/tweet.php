<?php echo Form::open() ?>

<dl>
	<dt><?php echo Form::label('tweet', 'Update Twitter Status?') ?></dt>
	<dd><?php echo Form::textarea('tweet', $tweet) ?></dd>
</dl>

<?php echo Form::submit(NULL, 'Send') ?>

<?php echo Form::close() ?>

<?php if ($response): ?>
<p>Response from Twitter:</p>
<?php echo $response ?>
<?php endif ?>
