<div class="page-mail">
	<div class="mail-nav">
		<?php echo $menu ?>
		<?php if(isset($toolbar)): ?>
		<?php echo $toolbar; ?>
		<?php endif; ?>
	</div>
	<div class="mail-container">
		<?php echo $content; ?>
	</div>
	<div class="clearfix"></div>
</div>



<div id="folder-modal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="no-margin-vr"><?php echo __('New folder'); ?></h4>
			</div>
			<form action="#" method="post">
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label" for="category-title"><?php echo __('Folder name'); ?></label>
						<div class="controls">
							<input type="text" name="folder-name" class="form-control" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __('Cancel'); ?></a>
					<button class="create-folder-btn btn btn-primary"><?php echo __('Save'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>