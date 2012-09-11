<div id="pageEditParts">
<?php
	$index = 1;

	foreach ( $page_parts as $page_part )
	{
		echo View::factory('page/blocks/part_edit', array(
			'index' => $index,
			'page_part' => $page_part,
			'permissions' => $permissions
		));

		$index++;
	}
?>

</div><!--/#pageEditParts-->
<div class="clearfix"></div>
<?php if ( AuthUser::hasPermission( array( 'administrator', 'developer' ) ) ): ?>
	<div id="pageEditPartAdd">
		<?php
		echo Form::button( NULL, UI::icon( 'plus' ) . ' ' . __( 'Add page part' ), array(
			'id' => 'pageEditPartAddButton'
		) );
		?>
	</div>
<?php endif; ?>