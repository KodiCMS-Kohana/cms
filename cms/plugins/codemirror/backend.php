<?php defined('SYSPATH') or die('No direct access allowed.');

Plugins::addJavascript('codemirror', 'vendors/CodeMirror-2.33/lib/codemirror.js');
Plugins::addStylesheet('codemirror', 'vendors/CodeMirror-2.33/lib/codemirror.css');
Plugins::addJavascript('codemirror', 'vendors/CodeMirror-2.33/mode/clike/clike.js');
Plugins::addJavascript('codemirror', 'vendors/CodeMirror-2.33/mode/xml/xml.js');
Plugins::addJavascript('codemirror', 'vendors/CodeMirror-2.33/mode/javascript/javascript.js');
Plugins::addJavascript('codemirror', 'vendors/CodeMirror-2.33/mode/css/css.js');
Plugins::addJavascript('codemirror', 'vendors/CodeMirror-2.33/mode/htmlmixed/htmlmixed.js');
Plugins::addJavascript('codemirror', 'vendors/CodeMirror-2.33/mode/php/php.js');
Plugins::addJavascript('codemirror', 'codemirror.js');


// Add tinymce to filter's list
Filter::add('codemirror');