<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

// Add behaviors
Behavior::add('search_result', 'search/search.php');

// Add navigation section
Model_Navigation::add_section('Other', 'Search', 'plugin/search', array(), 200);