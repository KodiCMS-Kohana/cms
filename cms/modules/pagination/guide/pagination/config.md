# Pagination Configuration

[Pagination] uses 6 settings: `current_page`, `total_items`, `items_per_page`, `view`, `auto_hide` and `first_page_in_url`.

## Configuration Examples

This example shows the default configuration:

	return array(
	
		// Application defaults
		'default' => array(
			'current_page'      => array('source' => 'query_string', 'key' => 'page'),  // source: "query_string" or "route"
			'total_items'       => 0,
			'items_per_page'    => 10,
			'view'              => 'pagination/basic',
			'auto_hide'         => TRUE,
			'first_page_in_url' => FALSE,
		),
	);

This is an example with multiple configurations:

	return array(
	
		// Application defaults
		'default' => array(
			'current_page'      => array('source' => 'query_string', 'key' => 'page'),
			'total_items'       => 0,
			'items_per_page'    => 10,
			'view'              => 'pagination/basic',
			'auto_hide'         => TRUE,
			'first_page_in_url' => FALSE,
		),
	
		// Second configuration
		'pretty' => array(
			'current_page'      => array('source' => 'route', 'key' => 'page'),
			'total_items'       => 0,
			'items_per_page'    => 20,
			'view'              => 'pagination/pretty',
			'auto_hide'         => TRUE,
			'first_page_in_url' => FALSE,
		),
	);



## Settings

### current_page

The `current_page` setting tells Pagination where to look to find the current page number.
There are two options for the `source` of the page number: `query_string` and `route`.
The `key` index in the configuration array tells Pagination what name to look for when it's searching in the query string or route.

This configuration informs Pagination to look in the query string for a value named `page`:

	'current_page'      => array('source' => 'query_string', 'key' => 'page'),

If you have a route setup with the page number in the actual URL like this:

	Route::set('city_listings', '<city>listings(/<page_num>)', array('page_num' => '[0-9]+'))
		->defaults(array(
			'controller' => 'city',
			'action' => 'listings'
		));

then you would use a setting like this:

	'current_page'   => array('source' => 'route', 'key' => 'page_num'),


### total_items

`total_items` is a setting you will most likely pass in during runtime after figuring out exactly how many items you have. It can be set to zero in the configuration for now.

### items_per_page

Self explanatory. This is the maximum items to show on each page. Pagination determines the total number of pages based off of this number.

### view

The `view` setting should be a path to a Pagination view file.

### auto_hide

If `auto_hide` is set to `TRUE` then Pagination will automatically hide whenever there's only one page of items.

### first_page_in_url

If you want Pagination to add the page number to the first page's link then set this setting to `TRUE` otherwise leave it as `FALSE`.


