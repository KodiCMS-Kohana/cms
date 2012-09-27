<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<div class="page-header">
	<h1><?php echo __('Snippets'); ?></h1> 
</div>

<div class="map">
	
	<div class="well page-actions">
		<?php echo UI::button(__('Add snippet'), array(
			'href' => 'snippet/add', 'icon' => UI::icon('plus')
		)); ?>
	</div>
	
	<table class=" table table-striped table-hover" id="SnippetList">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Snippet name'); ?></th>
				<th><?php echo __('Direction'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($snippets as $snippet): ?>
			<tr>
				<th class="name">
					<?php echo HTML::anchor(URL::site('snippet/edit/'.$snippet->name), HTML::image('images/snippet.png') .' '. $snippet->name); ?>
				</th>
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
</div><!--/#snippetMap-->