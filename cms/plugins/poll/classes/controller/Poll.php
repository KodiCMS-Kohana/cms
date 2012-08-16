<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Poll extends Controller_System_Plugin {
	
	public function action_index() 
	{
		$polls = Model_Polls::instance()->get_all();
		$this->template->content = View::factory('poll/index', array(
			'polls' => $polls
		));
	}

	public function action_send()
	{
		$poll_id = Arr::get($_POST, 'poll_id');
		$option_id = Arr::get($_POST, 'option_id');
		
		
		if($poll_id === NULL OR $option_id === NULL)
		{
			$this->go_back();
		}
		
		if ( Plugins::isEnabled( 'captcha' ) )
		{
			$captcha = Arr::get($_POST, 'captcha');

			if(!captcha_check($captcha))
			{
				$this->go_back();
			}
		}
		
		$poll = Model_Polls::instance()->get_poll( $poll_id );
		$poll->add_vote($option_id);

		$this->go_back();
	}
}