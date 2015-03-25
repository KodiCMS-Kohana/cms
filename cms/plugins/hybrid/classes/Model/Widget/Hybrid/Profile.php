<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Hybrid_Profile extends Model_Widget_User_Profile {
	
	/**
	 *
	 * @var array 
	 */
	public $doc_fields = array();
	
	/**
	 *
	 * @var array 
	 */
	public $doc_fetched_widgets = array();
	
	/**
	 * 
	 * @param array $data
	 */
	public function set_values(array $data) 
	{
		$this->doc_fields = $this->doc_fetched_widgets = array();
		parent::set_values($data);
		return $this;
	}
	
	/**
	 * 
	 * @param array $fields
	 * @return \Model_Widget_Hybrid_Profile
	 */
	public function set_field($fields = array())
	{
		if (!is_array($fields))
		{
			return;
		}

		foreach ($fields as $f)
		{
			if (isset($f['id']))
			{
				$this->doc_fields[] = (int) $f['id'];

				if (isset($f['fetcher']))
				{
					$this->doc_fetched_widgets[(int) $f['id']] = (int) $f['fetcher'];
				}
			}
		}

		return $this;
	}

	public function backend_data()
	{
		$this->ds_id = (int) Plugins::setting('hybrid', 'user_profile_ds_id');
		return array(
			'profile_ds_id' => $this->ds_id
		);
	}
	
	public function on_page_load()
	{
		parent::on_page_load();

		$user = $this->get_user();

		if (!$user->loaded() AND $this->throw_404 === TRUE)
		{
			$this->_ctx->throw_404('Profile not found');
		}

		if ($this->block == 'PRE' AND $user->loaded())
		{
			if (($profile = $this->get_profile($user->id)) !== NULL)
			{
				$page = $this->_ctx->get_page();

				$this->_ctx->set('widget_profile_id', $profile['id']);
				$page->meta_params(array(
					'profile_username' => $profile['header']
				));
			}
		}
		else if ($user->loaded())
		{
			$page = $this->_ctx->get_page();

			$page->meta_params(array(
				'profile_username' => $user->username
			));

			$this->_ctx->set('widget_profile_id', $user->id);
			$this->_ctx->set('widget_profile_username', $user->username);
		}
	}

	/**
	 * 
	 * @return array [$user_found, $user, $profile]
	 */
	public function fetch_data()
	{
		$user = $this->get_user();
		$profile = NULL;

		if ($user->loaded())
		{
			$profile = $this->get_profile($user->id);
		}

		return array(
			'user_found' => $user->loaded(),
			'user' => $user,
			'profile' => $profile
		);
	}

	public function get_profile($user_id)
	{
		$ds_id = (int) Plugins::setting('hybrid', 'user_profile_ds_id');

		$profile = NULL;

		if (
			!empty($ds_id)
			AND ( $agent = DataSource_Hybrid_Agent::instance($ds_id)) !== NULL
		)
		{
			$fields = $agent->get_fields();
			$fid = NULL;
			foreach ($fields as $field)
			{
				if ($field->key == 'profile_id')
				{
					$fid = $field->id;
					break;
				}
			}

			$profile = $agent->get_document((int) $user_id, $this->doc_fields, $fid);
			$recurse = 3;

			if (!empty($profile))
			{
				$hybrid_fields = $agent->get_fields();
				foreach ($profile as $key => $value)
				{
					if (!isset($hybrid_fields[$key]))
					{
						continue;
					}

					$field = & $hybrid_fields[$key];

					$profile['_' . $field->key] = $profile[$key];
					$profile[$field->key] = $field->fetch_widget_field($this, $field, $profile, $key, $recurse);
					unset($profile[$key]);
				}
			}
		}

		return $profile;
	}
}