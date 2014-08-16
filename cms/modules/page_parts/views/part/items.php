<?php echo View::factory('part/item'); ?>

<div id="pageEditParts"></div>

<?php if( ACL::check('page.parts')): ?>
<div id="pageEditPartsPanel" class="panel-heading">
	<?php echo UI::button( __( 'Add page part' ), array(
		'id' => 'pageEditPartAddButton', 
		'icon' => UI::icon( 'plus' ),
		'hotkeys' => 'ctrl+a'
	) ); ?>
</div>
<?php endif; ?>