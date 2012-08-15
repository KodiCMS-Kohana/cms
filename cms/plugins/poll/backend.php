<?php if(!defined('CMS_ROOT')) die;

// Autoload models
AutoLoader::addFile('poll',   PLUGINS_ROOT.'/poll/models/poll.php');
AutoLoader::addFile('polls',   PLUGINS_ROOT.'/poll/models/polls.php');

include PLUGINS_ROOT . '/poll/config.php';

// Add routes
Dispatcher::addRoute('/'.ADMIN_DIR_NAME.'/plugin/poll/', 'plugin/poll/index');

// Add controller
Plugin::addController('poll', 'poll', array('editor', 'developer', 'administrator'));

// Add navigation section
Plugin::addNav('Other', __('Polls'), 'plugin/poll', array('editor','developer','administrator') );