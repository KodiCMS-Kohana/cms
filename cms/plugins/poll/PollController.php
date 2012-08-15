<?php

if ( !defined( 'CMS_ROOT' ) )
	die;

// Autoload models
AutoLoader::addFile('poll',   PLUGINS_ROOT.'/poll/models/poll.php');

class PollController extends PluginController {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->setLayout('backend');
	}

	public function index()
	{
		$polls = Polls::instance()->get_all();
		$this->display('poll/views/index', array(
			'polls' => $polls
		));
	}

	public function send()
	{
		$poll_id = Arr::get($_POST, 'poll_id');
		$option_id = Arr::get($_POST, 'option_id');
		
		
		if($poll_id === NULL OR $option_id === NULL)
		{
			redirect( $_SERVER['HTTP_REFERER'] );
		}
		
		if ( Plugin::isEnabled( 'captcha' ) )
		{
			$captcha = Arr::get($_POST, 'captcha');

			if(!captcha_check($captcha))
			{
				redirect( $_SERVER['HTTP_REFERER'] );
			}
		}
		
		$poll = polls::instance()->get_poll( $poll_id );
		$poll->add_vote($option_id);
		
		

		redirect( $_SERVER['HTTP_REFERER'] );
	}

}