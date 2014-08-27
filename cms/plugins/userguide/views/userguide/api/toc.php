<?php echo UI::page_header(__( 'Available Classes' )); ?>

<input type="text" placeholder="<?php echo __('Filter'); ?>" class="form-control" name="search" id="api-filter-box">

<script type="text/javascript">
$.fn.extend({
	filter_content: function(search){
		var search_regex = new RegExp(search, 'gi');

		// Run through each .class definition
		$('.panel', this).each(function(k, container){
			if (search == '') {
				// If search box is empty show everything without doing any regex
				$(container).show();
				$('li', container).show();

				// Continue
				return true;
			}
//				
			// Run through every anchor
			$('a', $(container)).each(function(k, anchor){
				// Keep track of the li
				var $parent = $(anchor).closest('li');

				if ($(anchor).text().match(search_regex)) {
					// Show the li and .class parent if its a match
					$parent.show();
					$(container).show();
				} else {
					// Otherwise we can only assume to hide the li
					$parent.hide();
				}
			});

			if ($('li:visible', $(container)).length == 0) {
				// If there are not any visible li's, the entire .class section needs to hide
				$(container).hide();
			} else {
				// Otherwise show the .class container
				$(container).show();
			}
		});
	},

	api_filter: function(api_container_selector){
		$(this).keyup(function(){
			// Run the filter method on this value
			$(api_container_selector).filter_content($(this).val());
		});
	}
});

$(document).ready(function(){
    $('#api-filter-box').api_filter('.class-list');
});
</script>

<hr />

<div class="class-list">
		<?php $i = 1; foreach ($classes as $class => $methods): $link = $route->uri(array('class' => $class)) ?>
		<div class="col-md-4">
			<div class="panel">
				<div class="panel-heading">
					<span class="panel-title"><?php echo HTML::anchor($link, $class, NULL, NULL, TRUE) ?></span>
				</div>
				<div class="panel-body">
					<ul class="methods list-unstyled">
					<?php foreach ($methods as $method): ?>
						<li><?php echo HTML::anchor("{$link}#{$method}", "{$class}::{$method}", NULL, NULL, TRUE) ?></li>
					<?php endforeach ?>
					</ul>
				</div>
			</div>
		</div>
		<?php if(($i % 3) == 0): ?>
			
	</div>
	<div class="row">
			<?php endif; ?>
			<?php $i++; ?>
		
		<?php endforeach ?>
	</div>
</div>
