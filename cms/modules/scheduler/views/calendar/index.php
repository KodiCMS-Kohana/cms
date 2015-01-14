<div class="row">

	<div class="col-sm-3">
		<div class="panel">
			<form id="add-event-form">
				<div class="panel-heading">
					<span class="panel-title"><?php echo __('New event'); ?></span>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="btn-group btn-group-sm btn-group-justified" data-toggle="buttons">
							<?php foreach ($icons as $i => $icon): ?>
							<label class="btn btn-default btn-flat <?php if ($i == 0): ?>active<?php endif; ?>">
								<?php echo Form::radio('event_icon', $icon, $i == 0); ?>
								<?php echo UI::icon($icon . ' fa-lg'); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="form-group">
						<input class="form-control" name="event_title" maxlength="40" type="text" placeholder="<?php echo __('Event Title'); ?>">
					</div>
					<div class="form-group">
						<div class="input-group">
							<?php echo Form::input('event_start', NULL, array(
								'class' => 'datetimepicker form-control',
								'size' => 15,
								'placeholder' => __('Event start')
							)); ?>
							<div class="input-group-addon">-</div>
							<?php echo Form::input('event_end', NULL, array(
								'class' => 'datetimepicker form-control',
								'size' => 15,
								'placeholder' => __('Event end')
							)); ?>
						</div>
					</div>
					
					<div class="checkbox">
						<label>
							<?php echo Form::checkbox('event_only_for_me', 1, TRUE); ?> <?php echo __('Only for me'); ?>
						</label>
					</div>

					<div class="form-group">
						<label><?php echo __('Select Event Color'); ?></label>
						<div class="btn-group btn-group-justified" data-toggle="buttons">
							<?php foreach ($colors as $i => $color): ?>
							<label class="btn no-border bg-<?php echo $color; ?> <?php if ($i == 0): ?>active<?php endif; ?>">
								<?php echo Form::radio('event_color', $color, $i == 0); ?>
								<?php echo UI::icon('check'); ?>
							</label>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-default" type="submit" id="add-event"><?php echo __('Add Event'); ?></button>
				</div>
			</form>
		</div>
	</div>
	<div class="col-sm-9">
		<div class="panel">
			<div id="calendar" class="panel-body"></div>
		</div>
	</div>
</div>
