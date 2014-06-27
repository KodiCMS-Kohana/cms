<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Reflink
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_Reflink extends Controller_System_Controller {

	public function action_index()
	{
		$code = $this->request->param( 'code' );
		if ( $code === NULL )
		{
			Model_Page_Front::not_found();
		}

		$reflink_model = ORM::factory( 'user_reflink', $code );

		try
		{
			Database::instance()->begin();
			Reflink::factory($reflink_model)->confirm();
			Database::instance()->commit();
		}
		catch ( Kohana_Exception $e )
		{
			Database::instance()->rollback();
			Messages::errors( $e->getMessage() );
		}
		
		$this->go( Route::get('user')->uri(array( 'action' => 'login' ) ) );
	}
}