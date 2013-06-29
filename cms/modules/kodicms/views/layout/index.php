<div class="widget widget-nopad">
	<div class="widget-header">
		<?php echo UI::button(__('Add layout'), array(
			'icon' => UI::icon( 'plus' ), 'href' => 'layout/add',
		)); ?>

		<?php echo UI::button(__('Rebuild blocks'), array(
			'icon' => UI::icon( 'refresh' ), 'href' => 'layout/rebuild',
			'class' => 'btn btn-inverse btn-mini'
		)); ?>
	</div>

	<div class="widget-content">
		<table class=" table table-striped table-hover" id="LayoutList">
			<colgroup>
				<col />
				<col width="150px" />
				<col width="100px" />
				<col width="100px" />
				<col width="100px" />
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('Layout name'); ?></th>
					<th><?php echo __('Modified'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Direction'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($layouts as $layout): ?>
				<tr>
					<th class="name">
						<?php echo HTML::image(ADMIN_RESOURCES . 'images/layout.png'); ?>
						<?php if( ! $layout->is_writable()): ?>
						<span class="label label-warning"><?php echo __('Read only'); ?></span>
						<?php endif; ?>
						<?php echo HTML::anchor('layout/edit/'.$layout->name, $layout->name, array('class' => ! $layout->is_writable() ? 'popup fancybox.iframe' : '')); ?>
						<?php if(count($layout->blocks()) > 0): ?>
						<span class="muted">
							<?php echo __('Layout blocks'); ?>: <?php echo implode(', ', $layout->blocks()); ?>
						</span>
						<?php endif; ?>
					</th>
					<td class="modified">
						<?php echo Date::format($layout->modified()); ?>
					</td>
					<td class="size">
						<?php echo Text::bytes( $layout->size()); ?>
					</td>
					<td class="direction">
						<?php echo UI::label('/layouts/' . $layout->name . EXT); ?>
					</td>
					<td class="actions">
						<?php echo UI::button(NULL, array(
							'icon' => UI::icon( 'remove' ), 'href' => 'layout/delete/'. $layout->name,
							'class' => 'btn btn-mini btn-confirm'
						)); ?>
					</td>
				</tr>

				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>