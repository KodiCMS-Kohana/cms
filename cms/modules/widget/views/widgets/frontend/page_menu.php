<?php if(!empty($header)): ?>
<h3><?php echo $header; ?></h3>
<?php endif; ?>

<div class="navbar">
	<?php echo recurse_page_menu( $pages ); ?>
</div>

<?php function recurse_page_menu( $pages ) {
	
	$return = '<ul class="nav">';
	
	foreach($pages as $p)
	{
		$return .= '<li>';
		$return .= HTML::anchor($p['uri'], $p['title'], array(
			'class' =>  $p['is_active'] ? 'current' : ''
		));
		
		if( !empty($p['childs']))
		{
			$return .= recurse_page_menu($p['childs']);
		}
		
		$return .= '</li>';
	}
	
	$return .= '</ul>';
	
	return $return;
}