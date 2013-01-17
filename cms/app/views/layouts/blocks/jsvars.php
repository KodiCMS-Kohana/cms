var CURRENT_URL			= '<?php echo Request::current()->url(TRUE) . URL::query() ; ?>';
var BASE_URL			= '<?php echo URL::site(ADMIN_DIR_NAME, TRUE); ?>';
var SITE_URL			= '<?php echo URL::base(TRUE); ?>';
var ADMIN_DIR_NAME		= '<?php echo ADMIN_DIR_NAME; ?>';
var ADMIN_RESOURCES		= '<?php echo ADMIN_RESOURCES; ?>';
var PUBLIC_URL			= '<?php echo PUBLIC_URL; ?>';
var PLUGINS_URL			= '<?php echo PLUGINS_URL; ?>';
var LOCALE				= '<?php echo I18n::lang(); ?>';
var TAG_SEPARATOR		= '<?php echo Model_Tag::SEPARATOR; ?>';

var PAGE_STATUSES = '<?php echo json_encode(Model_Page::statuses()); ?>';
var PAGE_LOGINS = '<?php echo json_encode(Model_Page::logins()); ?>';

var BEHAVIORS = '<?php echo json_encode(Behavior::findAll()); ?>';
var FILTERS = '<?php echo json_encode(Filter::findAll()); ?>';
