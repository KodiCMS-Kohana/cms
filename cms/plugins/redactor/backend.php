<?php defined('SYSPATH') or die('No direct access allowed.');

Plugins::addJavascript('redactor', 'redactor.js');
Plugins::addJavascript('redactor', 'vendors/redactor/ru.js');
Plugins::addJavascript('redactor', 'vendors/redactor/redactor.min.js');
Plugins::addStylesheet('redactor', 'vendors/redactor/redactor.css');


// Add tinymce to filter's list
Filter::add('redactor');