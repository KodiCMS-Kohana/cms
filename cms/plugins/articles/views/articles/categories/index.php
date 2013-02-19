<div class="map widget">
	<div class="widget-header">
		<?php echo UI::button(__('Add category'), array(
			'icon' => UI::icon( 'plus' ), 'href' => 'articles/categories/add'
		)); ?>
	</div>

	<?php if($categories->count() > 0): ?>
	<div class="widget-content widget-nopad">
		<table class=" table table-striped table-hover" id="CAtegoriesList">
			<colgroup>
				<col>
				<col width="300px">
				<col width="100px">
			</colgroup>
			<thead>
				<tr>
					<th>Название</th>
					<th>Slug</th>
					<th>Действия</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($categories as $category): ?>
				<tr>
					<th><?php echo UI::icon('folder-close'); ?> <?php echo HTML::anchor('articles/categories/edit/' . $category->id, $category->title); ?></th>
					<td><?php echo $category->slug; ?></td>
					<td class="actions">
						<?php echo UI::button(NULL, array(
							'icon' => UI::icon( 'remove' ),
							'class' => 'btn btn-mini btn-confirm',
							'href' => URL::backend('articles/categories/delete/'. $category->id)
						)); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	
	<?php else: ?>
	<div class="widget-content">
			<h2><?php echo __('No categories'); ?></h2>
		<?php endif; ?>
	</div>
</div>