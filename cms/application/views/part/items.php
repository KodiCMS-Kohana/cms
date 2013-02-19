<?php echo View::factory('part/item'); ?>

<div id="pageEditParts"></div>

<?php if ( AuthUser::hasPermission( array( 'administrator', 'developer' ) ) ): ?>
	<div id="pageEditPartsPanel" class="widget-header widget-no-border-radius">
		<?php echo UI::button( __( 'Add page part' ), array(
			'id' => 'pageEditPartAddButton', 'icon' => UI::icon( 'plus' )
		) ); ?>
	</div>
<?php endif; ?>