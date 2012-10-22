<fieldset>
	<legend><?php echo __( 'Environment Tests' ); ?></legend>
	<br />
	<table class="table table-striped">
		<tr>
			<th><?= __( 'PHP Version' ); ?></th>
			<?php if ( version_compare( PHP_VERSION, '5.3.3', '>=' ) ): ?>
				<td class="pass"><?php echo PHP_VERSION ?></td>
			<?php else: $failed = TRUE ?>
				<td class="fail"><?= __( 'CMS requires PHP 5.3.3 or newer, this version is' ); ?> <?php echo PHP_VERSION ?>.</td>
			<?php endif ?>
		</tr>
		<tr>
			<th><?= __( 'Application Directory' ); ?></th>
			<?php if ( is_dir( APPPATH ) AND is_file( APPPATH . 'bootstrap' . EXT ) ): ?>
				<td class="pass"><?php echo APPPATH ?></td>
			<?php else: $failed = TRUE ?>
				<td class="fail">The configured <code>application</code> directory does not exist or does not contain required files.</td>
			<?php endif ?>
		</tr>
		<tr>
			<th><?= __( 'Cache Directory' ); ?></th>
			<?php if ( is_dir( APPPATH ) AND is_dir( APPPATH . 'cache' ) AND is_writable( APPPATH . 'cache' ) ): ?>
				<td class="pass"><?php echo APPPATH . 'cache/' ?></td>
			<?php else: $failed = TRUE ?>
				<td class="fail">The <code><?php echo APPPATH . 'cache/' ?></code> directory is not writable.</td>
			<?php endif ?>
		</tr>
		<tr>
			<th>PCRE UTF-8</th>
			<?php if ( !@preg_match( '/^.$/u', 'ñ' ) ): $failed = TRUE ?>
				<td class="fail"><a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support.</td>
			<?php elseif ( !@preg_match( '/^\pL$/u', 'ñ' ) ): $failed = TRUE ?>
				<td class="fail"><a href="http://php.net/pcre">PCRE</a> has not been compiled with Unicode property support.</td>
			<?php else: ?>
				<td class="pass">Pass</td>
			<?php endif ?>
		</tr>
		<tr>
			<th><?= __( 'SPL Enabled' ); ?></th>
			<?php if ( function_exists( 'spl_autoload_register' ) ): ?>
				<td class="pass">Pass</td>
			<?php else: $failed = TRUE ?>
				<td class="fail">PHP <a href="http://www.php.net/spl">SPL</a> is either not loaded or not compiled in.</td>
			<?php endif ?>
		</tr>
		<tr>
			<th>Reflection Enabled</th>
			<?php if ( class_exists( 'ReflectionClass' ) ): ?>
				<td class="pass">Pass</td>
			<?php else: $failed = TRUE ?>
				<td class="fail">PHP <a href="http://www.php.net/reflection">reflection</a> is either not loaded or not compiled in.</td>
			<?php endif ?>
		</tr>
		<tr>
			<th>Filters Enabled</th>
			<?php if ( function_exists( 'filter_list' ) ): ?>
				<td class="pass">Pass</td>
			<?php else: $failed = TRUE ?>
				<td class="fail">The <a href="http://www.php.net/filter">filter</a> extension is either not loaded or not compiled in.</td>
			<?php endif ?>
		</tr>
		<tr>
			<th>Iconv Extension Loaded</th>
			<?php if ( extension_loaded( 'iconv' ) ): ?>
				<td class="pass">Pass</td>
			<?php else: $failed = TRUE ?>
				<td class="fail">The <a href="http://php.net/iconv">iconv</a> extension is not loaded.</td>
			<?php endif ?>
		</tr>
		<?php if ( extension_loaded( 'mbstring' ) ): ?>
			<tr>
				<th>Mbstring Not Overloaded</th>
				<?php if ( ini_get( 'mbstring.func_overload' ) & MB_OVERLOAD_STRING ): $failed = TRUE ?>
					<td class="fail">The <a href="http://php.net/mbstring">mbstring</a> extension is overloading PHP's native string functions.</td>
				<?php else: ?>
					<td class="pass">Pass</td>
				<?php endif ?>
			</tr>
		<?php endif ?>
		<tr>
			<th>Character Type (CTYPE) Extension</th>
			<?php if ( !function_exists( 'ctype_digit' ) ): $failed = TRUE ?>
				<td class="fail">The <a href="http://php.net/ctype">ctype</a> extension is not enabled.</td>
			<?php else: ?>
				<td class="pass">Pass</td>
			<?php endif ?>
		</tr>
		<tr>
			<th>URI Determination</th>
			<?php if ( isset( $_SERVER['REQUEST_URI'] ) OR isset( $_SERVER['PHP_SELF'] ) OR isset( $_SERVER['PATH_INFO'] ) ): ?>
				<td class="pass">Pass</td>
			<?php else: $failed = TRUE ?>
				<td class="fail">Neither <code>$_SERVER['REQUEST_URI']</code>, <code>$_SERVER['PHP_SELF']</code>, or <code>$_SERVER['PATH_INFO']</code> is available.</td>
			<?php endif ?>
		</tr>
	</table>
</fieldset>