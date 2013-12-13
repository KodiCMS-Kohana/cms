<div class="widget widget-nopad">
	<div class="widget-header">
		<?php if ( Acl::check( 'email_template.add')): ?>
		<?php echo UI::button(__('Add template'), array(
			'href' => Route::url( 'email_controllers', array('controller' => 'templates', 'action' => 'add')), 'icon' => UI::icon('plus')
		)); ?>
		<?php endif; ?>
	</div>
	
	<div class="widget-content">
		<table class="table table-striped table-hover">
			<colgroup>
				<col />
				<col />
				<col width="200px" />
				<col width="200px" />
				<col width="100px" />
				<col width="100px" />
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('Email subject'); ?></th>
					<th><?php echo __('Email type'); ?></th>
					<th><?php echo __('Email from'); ?></th>
					<th><?php echo __('Email to'); ?></th>
					<th><?php echo __('Status'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($templates as $tpl): ?>
				<tr class="item">
					<td class="name">
						<?php echo HTML::anchor(Route::url('email_controllers', array(
							'controller' => 'templates',
							'action' => 'edit',
							'id' => $tpl->id
						)), $tpl->subject); ?>
					</td>
					<td class="email_type">
						<?php echo HTML::anchor(Route::url('email_controllers', array(
							'controller' => 'types',
							'action' => 'edit',
							'id' => $tpl->type->id
						)), $tpl->type->name); ?>
					</td>
					<td class="email"><?php echo UI::label($tpl->email_from); ?></td>
					<td class="email"><?php echo UI::label($tpl->email_to); ?></td>
					<td class="status"><?php echo $tpl->status; ?></td>
					<td class="actions">
						<?php if ( Acl::check( 'email_template.delete')): ?>
						<?php echo UI::button(NULL, array(
							'href' => Route::url('email_controllers', array(
								'controller' => 'templates',
								'action' => 'delete',
								'id' => $tpl->id
							)), 
							'icon' => UI::icon('remove'),
							'class' => 'btn btn-mini btn-confirm'
						)); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php echo $pager; ?>