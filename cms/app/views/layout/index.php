<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="page-header">
	<h1><?php echo __('Layouts'); ?></h1>
</div>

<div class="map">
	
	<div class="well page-actions">
		<?php echo HTML::button(URL::site('layout/add'), __('Add layout'), 'plus'); ?>
	</div>
	
	<table class="table_list" id="LayoutList">
		<colgroup>
			<col />
			<col width="150px" />
			<col width="100px" />
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('Layout name'); ?></th>
				<th><?php echo __('Direction'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($layouts as $layout): ?>
			<tr>
				<th class="name">
					<?php echo HTML::anchor(URL::site( 'layout/edit/'.$layout->name), HTML::image('images/layout.png') .' '. $layout->name); ?>
				</th>
				<td class="direction">
					<?php echo HTML::label('/layouts/' . $layout->name . EXT); ?>
				</td>
				<td class="actions">
					<?php echo HTML::button(URL::site('layout/delete/'. $layout->name), NULL, 'remove', 'btn btn-mini btn-confirm'); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div><!--/#layoutMap-->