<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Other
 * @author		ButscHSter
 */
class Model_Widget_User_Profile extends Model_Widget_Decorator {
	
	public function set_values(array $data) 
	{
		parent::set_values($data);

		$profile_id_ctx = Arr::get($data, 'profile_id_ctx');
		$this->profile_id_ctx = empty($profile_id_ctx) ? $this->profile_id_ctx : $profile_id_ctx;
		
		return $this;
	}
	
	public function on_page_load()
	{
		parent::on_page_load();
		
		if( ! $this->get_user()->loaded() AND $this->throw_404 === TRUE )
		{
			$this->_ctx->throw_404('Profile not found');
		}
	}

	public function fetch_data()
	{
		$profile_id = $this->_ctx->get($this->profile_id_ctx);
		
		if(Valid::numeric($profile_id))
		{
			$user = ORM::factory('User', $profile_id);
		}
		else
		{
			$user = Auth::instance()->get_user(ORM::factory('User'));
		}

		return array(
			'user_found' => $user->loaded(),
			'user' => $user,
			'profile' => $user->profile
		);
	}
	
	public function get_user()
	{
		$profile_id = $this->_ctx->get($this->profile_id_ctx);
		
		if(Valid::numeric($profile_id))
		{
			$user = ORM::factory('User', $profile_id);
		}
		else
		{
			$user = Auth::instance()->get_user(ORM::factory('User'));
		}
		
		return $user;
	}

	public function get_profile_id()
	{
		return $this->_ctx->get($this->profile_id_ctx);
	}
}