<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS
 * @category	Controller
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Controller_Ajax_JSON extends Controller_System_Ajax {

	public $json = array(
		'status' => FALSE,
		'message' => NULL
	);

	public function after()
	{
		if ( is_array( $this->json ) )
		{
			$this->request->headers( 'Content-type', 'application/json' );
			$this->json = json_encode( $this->json );
		}

		$this->response->body( $this->json );
	}
}