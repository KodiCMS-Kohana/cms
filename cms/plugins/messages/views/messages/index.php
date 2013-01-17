<div class="map widget">
	
	<div class="widget-header">
		<div class="row-fluid">
			<?php echo UI::button(__('Send message'), array(
				'href' => 'messages/add', 'icon' => UI::icon('envelope'),
				'class' => 'btn btn-large'
			)); ?>
		</div>
	</div>
	
	<?php if(count($messages) > 0): ?>
	<div class="widget-content widget-nopad">
		<table class=" table table-striped table-hover" id="SnippetList">
			<colgroup>
				<col width="50px" />
				<col />
				<col width="150px" />
				<col width="180px" />
			</colgroup>
			<thead>
				<tr>
					<th><input type="checkbox" name="check_all" /></th>
					<th><?php echo __('Title'); ?></th>
					<th><?php echo __('Author'); ?></th>
					<th><?php echo __('Date created'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($messages as $message): ?>
				<tr <?php if( ! $message->is_read()): ?>class="info"<?php endif; ?>>
					<td><?php echo Form::checkbox('item['.$message->id.']'); ?></td>
					<td><strong><?php echo HTML::anchor('messages/view/' . $message->id, $message->title); ?></strong></td>
					<td><?php echo $message->author; ?></td>
					<td><?php echo $message->created(); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php else: ?>
	<div class="widget-content">
		<h1><?php echo __('You dont have messages'); ?></h1>
	</div>
	<?php endif; ?>
	
</div>