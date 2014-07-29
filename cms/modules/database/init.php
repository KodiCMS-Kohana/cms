<?php defined('SYSPATH') or die('No direct access allowed.');

Kohana::$config->attach(new Config_Database);
Kohana::$log->attach(new Log_Database('logs'));