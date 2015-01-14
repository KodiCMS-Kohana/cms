<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Hybrid
 * @category	Plugin
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Plugin_Hybrid extends Plugin_Decorator {
	
	public function set_settings(array $data)
	{
		if (!empty($data['user_profile_ds_id']))
		{
			$profile_ds_id = $data['user_profile_ds_id'];

			$ds = Datasource_Data_Manager::load($profile_ds_id);
			if ($ds === NULL OR $ds->type() != 'hybrid')
			{
				return;
			}
		}

		return parent::set_settings($data);
	}

	public function sections()
	{
		$options = Datasource_Data_Manager::get_all_as_options('hybrid');

		foreach ($options as $id => $name)
		{
			$ds = Datasource_Data_Manager::load($id);

			if ($ds === NULL)
			{
				continue;
			}

			if (!in_array('profile_id', $ds->agent()->get_field_names()))
			{
				unset($options[$id]);
			}
		}


		return $options;
	}

}