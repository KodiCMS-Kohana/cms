<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		ButscHSter
 */
class KodiCMS_Controller_System_Ajax_Error extends Controller_System_Template 
{
	public $auth_required = FALSE;

	public function action_index()
	{
		$this->json['message'] = '';
		$this->json['uri'] = URL::site( rawurldecode( Request::$initial->uri() ) );
		if ( $message = rawurldecode( $this->request->param( 'id' ) ) )
		{
			$this->json['message'] = $message;
		}
	}

}