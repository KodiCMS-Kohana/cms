<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * @package		KodiCMS/SSO
 * @category	Driver
 * @author		ButscHSter
 */
abstract class Kohana_SSO_Driver_OAuth2_Vk extends SSO_Driver_OAuth2 {

	protected $_provider = 'vk';

	/**
	 * @param   string  $user object (response from provider)
	 * @return  Array
	 */
	protected function _get_user_data($user)
	{
		$response = $user;
		$user = json_decode($user);
		$user = current($user->response);

		$login = trim($user->first_name.' '.$user->last_name);
		$displayname = isset($user->nickname) && ! empty($user->nickname) ? $user->nickname : $login;
		return array(
			'service_id'    => $user->uid,
			'service_name'  => $displayname,
			'name'			=> $login,
			'service_type'  => 'OAuth2.Vk',
			'email'         => NULL,
			'avatar'        => ! empty($user->photo_medium) ? $user->photo_medium : $user->photo,
			'response'		=> $response
		);
	}

	protected function _url_verify_credentials(OAuth2_Token_Access $token)
	{
		return 'https://api.vk.com/method/users.get';
	}

	protected function _credential_params(OAuth2_Client $client, OAuth2_Token_Access $token)
	{
		return array(
			'uids'         => $token->user_id,
			'access_token' => $token->token,
			'fields'       => 'uid,first_name,last_name,nickname,sex,bdate,city,country,photo,photo_medium,photo_big,photo_rec',
		);
	}

}
