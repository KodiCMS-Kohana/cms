<?php

if ( !defined( 'CMS_ROOT' ) )
	die;

$poll = poll::init(1)
	->add_title('Как вы регистрируете фирму?')
	->only_unique_ip(FALSE)
	->add_option(1, 'обращаюсь к специалистам')
	->add_option(2, 'самостоятельно')
	->add_option(3, 'поручаю сотрудникам')
	->add_option(4, 'еще не регистрировал, изучаю');

polls::instance()->add_poll($poll);