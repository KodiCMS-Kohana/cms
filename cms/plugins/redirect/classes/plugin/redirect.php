<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Plugin_Redirect extends Plugin_Decorator {
	
	public function default_settings()
	{
		$settings = parent::default_settings();
		
		$settings['domain'] = $_SERVER['HTTP_HOST'];
		$settings['check_url_suffix'] = Config::YES;

		return $settings;
	}
	
	public function set_settings( array $data )
	{
		if( !isset($data['check_url_suffix']) ) $data['check_url_suffix'] = Config::NO;
		
		if( ! $this->is_valid_domain_name($data['domain']))
		{
			$data['domain'] = NULL;
		}

		return parent::set_settings($data);
	}
	
	public function is_valid_domain_name($domain_name)
	{
		return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
				&& preg_match("/^.{1,253}$/", $domain_name) //overall length check
				&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
	}
}