<?php if(!defined('CMS_ROOT')) die;

// Autoload models
AutoLoader::addFile('poll',   PLUGINS_ROOT.'/poll/models/poll.php');
AutoLoader::addFile('polls',   PLUGINS_ROOT.'/poll/models/polls.php');

include PLUGINS_ROOT . '/poll/config.php';

Dispatcher::addRoute('/send_poll', '/plugin/poll/send');
Plugin::addController('poll', 'poll', array());