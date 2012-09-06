<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

// Add behaviors
Behavior::add('search_result', 'search/search.php');

// Add navigation section
Model_Navigation::add_section('Settings', 'Search', 'plugin/search', array('administrator'), 200);