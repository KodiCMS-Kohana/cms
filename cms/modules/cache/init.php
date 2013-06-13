<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set the default cache driver
 */
Cache::$default = defined('CACHE_TYPE') ? CACHE_TYPE : 'file';