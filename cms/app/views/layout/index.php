<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

<div class="page-header">
	<h1><?php echo __('Layouts'); ?></h1>
</div>

<div class="map">
	
	<div class="well page-actions">
		<?php echo UI::button(__('Add layout'), array(
			'icon' => UI::icon( 'plus' ), 'href' => 'layout/add'
		)); ?>
	</div>
	
	<table class=" table table-striped table-hover" id="LayoutList">
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
</div><!--/#layoutMap-->