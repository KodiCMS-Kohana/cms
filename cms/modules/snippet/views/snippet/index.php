 <div class="widget widget-nopad outline_inner">
	<div class="widget-header">
		<?php echo UI::button(__('Add snippet'), array(
			'href' => 'snippet/add', 'icon' => UI::icon('plus'),
		)); ?>
	</div>

	<div class="widget-content">
		<table class=" table table-striped table-hover" id="SnippetList">
			<colgroup>
				<col />
				<col width="150px" />
				<col width="100px" />
				<col width="100px" />
				<col width="100px" />
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('Snippet name'); ?></th>
					<th><?php echo __('Modified'); ?></th>
					<th><?php echo __('Size'); ?></th>
					<th><?php echo __('Direction'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($snippets as $snippet): ?>
				<tr>
					<th class="name">
						<?php echo HTML::image(ADMIN_RESOURCES . 'images/snippet.png'); ?>
						<?php if( ! $snippet->is_writable()): ?>
						<span class="label label-warning"><?php echo __('Read only'); ?></span>
						<?php endif; ?>
						
						<?php echo HTML::anchor('snippet/edit/'.$snippet->name, $snippet->name, array('class' => ! $snippet->is_writable() ? 'popup fancybox.iframe' : '')); ?>
					</th>
					<td class="modified">
						<?php echo Date::format($snippet->modified()); ?>
					</td>
					<td class="size">
						<?php echo Text::bytes( $snippet->size()); ?>
					</td>
					<td class="direction">
						<?php echo UI::label('/snippets/' . $snippet->name . EXT); ?>
					</td>
					<td class="actions">
						<?php echo UI::button(NULL, array(
							'href' => 'snippet/delete/'. $snippet->name, 'icon' => UI::icon('remove'),
							 'class' => 'btn btn-mini btn-confirm'
						)); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>