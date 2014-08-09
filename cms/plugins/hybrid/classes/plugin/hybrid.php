<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

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
				continue;

			if (!in_array('profile_id', $ds->agent()->get_field_names()))
			{
				unset($options[$id]);
			}
		}


		return $options;
	}

}