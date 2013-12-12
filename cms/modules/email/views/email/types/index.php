<div class="widget widget-nopad">
	<div class="widget-header">
		<?php if ( Acl::check( 'email_type.add')): ?>
		<?php echo UI::button(__('Add email type'), array(
			'href' => Route::url( 'email_controllers', array('controller' => 'types', 'action' => 'add')), 'icon' => UI::icon('plus')
		)); ?>
		<?php endif; ?>
	</div>
	
	<div class="widget-content">
		<table class="table table-striped table-hover">
			<colgroup>
				<col />
				<col width="200px" />
				<col width="100px" />
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('Email type name'); ?></th>
					<th><?php echo __('Email type code'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($types as $type): ?>
				<tr class="item">
					<td class="name">
						<?php echo HTML::anchor(Route::url('email_controllers', array(
							'controller' => 'types',
							'action' => 'edit',
							'id' => $type->id
						)), $type->name); ?>
					</td>
					<td class="email_type">
						<?php echo UI::label($type->code); ?>
					</td>
					<td class="actions">
						<?php 
							echo UI::button(NULL, array(
								'href' => Route::url('email_controllers', array(
									'controller' => 'types',
									'action' => 'delete',
									'id' => $type->id
								)), 
								'icon' => UI::icon('remove'),
								'class' => 'btn btn-mini btn-confirm'
							));
						?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo $pager; ?>