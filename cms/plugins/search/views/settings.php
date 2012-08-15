<h1>
	<a href="<?php echo get_url( 'plugins' ); ?>"><?php echo __( 'Plugins' ); ?></a> &rarr;
	<?php echo __( 'Search settings' ); ?>
</h1>

<div class="box">
	<form class="form" action="<?php echo get_url( 'plugin/search/settings' ); ?>" method="post">

		<section>
			<label><?php echo __( 'Search query key' ); ?></label>
			<span>
				<input type="text" name="setting[search_query_key]" value="<?php echo(isset( $settings['search_query_key'] ) ? $settings['search_query_key'] : 'q'); ?>" />
			</span>
		</section>

		<section>
			<label><?php echo __( 'Search only in title' ); ?></label>
			<span>
				<select name="setting[search_only_title]" class="input-select">
					<option value="yes" <?php if ( isset( $settings['search_only_title'] ) && $settings['search_only_title'] == 'yes' ) echo('selected'); ?>><?php echo __( 'Yes' ); ?></option>
					<option value="no" <?php if ( isset( $settings['search_only_title'] ) && $settings['search_only_title'] == 'no' ) echo('selected'); ?>><?php echo __( 'No' ); ?></option>
				</select>
			</span>
		</section>

		<div class="box-buttons">
			<button type="submit" name="commit"><img src="images/check.png" /> <?php echo __( 'Save setting' ); ?></button>
			<?php echo __( 'or' ); ?> <a href="<?php echo get_url( 'plugins' ); ?>"><?php echo __( 'Cancel' ); ?></a>
		</div>

	</form>
</div>