<?php defined('SYSPATH') or die('No direct access allowed.');

Plugins::addJavascript('tagsinput', 'tagsinput.js');
Plugins::addJavascript('tagsinput', 'vendors/jquery-tags-input/jquery.tagsinput.min.js');
Plugins::addStylesheet('tagsinput', 'vendors/jquery-tags-input/jquery.tagsinput.css');


// Add tinymce to filter's list
Filter::add('redactor');