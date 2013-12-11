<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Assets::js('ace-library', ADMIN_RESOURCES . 'libs/ace/src-min/ace.js', 'global');
Assets::js('ace', ADMIN_RESOURCES . 'js/ace.js', 'global');
WYSIWYG::add('ace');