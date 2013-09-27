<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Cli User add for KodiCMS
 *
 * It can accept the following options:
 *  - username: Username (Required)
 *	- password: Password (Required)
 *  - roles: Role names separated by "," (Default role: login)
 *  - email: Email address (Required)
 */
class Task_Users_Add extends KodiCMS_Task_Users_Add {}