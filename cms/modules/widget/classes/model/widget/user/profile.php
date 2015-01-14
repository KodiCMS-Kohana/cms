<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_User_Profile extends Model_Widget_Decorator {
	
	/**
	 *
	 * @var ORM 
	 */
	protected $_user = NULL;
	
	protected $_data = array(
		'profile_id_ctx' => 'user_id'
	);

	public function set_values(array $data) 
	{
		parent::set_values($data);

		$profile_id_ctx = Arr::get($data, 'profile_id_ctx');
		$this->profile_id_ctx = empty($profile_id_ctx) 
			? $this->profile_id_ctx 
			: $profile_id_ctx;
		
		return $this;
	}
	
	public function on_page_load()
	{
		parent::on_page_load();

		$user = $this->get_user();

		if (!$user->loaded() AND $this->throw_404 === TRUE)
		{
			$this->_ctx->throw_404('Profile not found');
		}

		$page = $this->_ctx->get_page();

		$page->meta_params(array(
			'profile_username' => $user->username
		));

		$this->_ctx->set('widget_profile_id', $user->id);
		$this->_ctx->set('widget_profile_username', $user->username);
	}

	/**
	 * 
	 * @return array [$user_found, $user, $profile]
	 */
	public function fetch_data()
	{
		$user = $this->get_user();
		
		return array(
			'user_found' => $user->loaded(),
			'user' => $user,
			'profile' => $user->profile
		);
	}
	
	/**
	 * 
	 * @return ORM
	 */
	public function get_user()
	{
		if ($this->_user instanceof ORM)
		{
			return $this->_user;
		}

		$profile_id = $this->get_profile_id();
		
		if (is_string($profile_id) AND Valid::numeric($profile_id))
		{
			$this->_user = ORM::factory('User', $profile_id);
		}
		else
		{
			$this->_user = Auth::get_record(ORM::factory('User'));
			$this->_ctx->set($this->profile_id_ctx, $this->_user->id);
		}

		return $this->_user;
	}

	/**
	 * 
	 * @return integer
	 */
	public function get_profile_id()
	{
		return $this->_ctx->get($this->profile_id_ctx);
	}
}