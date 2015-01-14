<?php list($failed, $tests, $optional) = Installer_Environment::check(); ?>
<script>
	var failed = <?php echo $failed ? 'true' : 'false'; ?>;
	$('td.fail').parent().addClass('fail');
</script>

<?php if ($failed === TRUE): ?>
<div class="alert alert-danger alert-dark no-margin-b padding-sm-vr"><?php echo UI::icon('exclamation-triangle fa-lg'); ?> <?php echo __('Kohana may not work correctly with your environment.'); ?></div>
<?php else: ?>
<div class="alert alert-success alert-dark no-margin-b padding-sm-vr"><?php echo UI::icon('check fa-lg'); ?> <?php echo __('Your environment passed all requirements.'); ?></div>
<?php endif ?>

<div id="env_test">
	<table class="table table-hover">
		<colgroup>
			<col width="300px" />
			<col />
		</colgroup>
		<tbody>
			<?php foreach ($tests as $test): ?>
			<tr class="<?php echo !$test['failed'] ? '' : 'danger'; ?>">
				<th><?php echo Arr::get($test, 'title'); ?></th>
				<td>
					<div class="<?php echo !$test['failed'] ? 'text-success' : ''; ?>"><?php echo $test['message']; ?></div>
					<?php $notice = Arr::get($test, 'notice'); if (is_array($notice)): ?>
					<br />
					<div class="<?php echo Arr::get($notice, 'class'); ?> padding-xs-vr no-margin-b">
						<?php echo UI::icon('lightbulb-o fa-lg'); ?>  <?php echo Arr::get($notice, 'message'); ?>
					</div>
					<?php endif ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div class="panel-heading">
	<span class="panel-title"><?php echo __( 'Optional Tests' ); ?></span>
</div>

<p class="alert alert-info alert-dark no-margin-b padding-sm-vr"><?php echo UI::icon('lightbulb-o fa-lg'); ?> <?php echo __('The following extensions are not required to run the Kohana core, but if enabled can provide access to additional classes.'); ?></p>
<div id="optional_test">
	<table class="table table-striped">
		<colgroup>
			<col width="300px" />
			<col />
		</colgroup>
		<tbody>
			<?php foreach ($optional as $test): ?>
			<tr class="<?php echo !$test['failed'] ? '' : 'info'; ?>">
				<th><?php echo Arr::get($test, 'title'); ?></th>
				<td>
					<div class="<?php echo !$test['failed'] ? 'text-success' : ''; ?>"><?php echo $test['message']; ?></div>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbdoy>
	</table>
</div>