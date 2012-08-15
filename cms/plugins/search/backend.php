<?php if(!defined('CMS_ROOT')) die;

// Add behaviors
Behavior::add('search', 'search/search.php');

// Add controller
Plugin::addController('search', 'search', array('developer','administrator'));