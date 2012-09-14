<?php defined('SYSPATH') or die('No direct access allowed.');

Plugins::addJavascript('filemanager', 'filemanager.js');

// Add navigation section
Model_Navigation::add_section('Content', 'File Manager', 'filemanager', array('administrator', 'developer', 'editor'), 999);