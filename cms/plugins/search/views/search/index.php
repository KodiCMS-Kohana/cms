<div class="page-header">
	<h1><?php echo __( 'Search index' ); ?></h1>
</div>

<div class="well page-actions">
	<?php echo HTML::button(URL::site('plugin/search/indexer'), __('Reindex pages'), 'plus'); ?>
	<?php echo __('Total pages in index: :total', array(':total' => $total_pages)); ?>
</div>

<table id="IndexList" class="table_list">
	<colgroup>
		<col width="50px">
		<col>
	</colgroup>
	<thead>
		<tr>
			<th>ID</th>
			<th>Title</th>
			<th>Content</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($pages as $page): ?>
		<tr>
			<td class="id"><?php echo $page->id; ?></td>
			<th>
				<h6><?php echo $page->page_title; ?></h6>
			</th>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>