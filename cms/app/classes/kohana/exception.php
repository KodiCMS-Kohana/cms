<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Kohana_Exception extends Kohana_Kohana_Exception {

	public static function handler( Exception $e )
	{
		if ( Setting::get( 'debug' ) == 'yes' )
		{
			parent::handler( $e );
		}
		else
		{
			// Log error
			Kohana::$log->add( Log::ERROR, parent::text( $e ) )->write();

			$params = array
			(
				'code'  => 500,
				'message' => rawurlencode($e->getMessage())
			);

			if ($e instanceof HTTP_Exception)
			{
				$params['code'] = $e->getCode();
			}
				
			try
			{
				echo Request::factory( Route::get('error')->uri($params) )
					->execute()
					->send_headers()
					->body();
			}
			catch ( Exception $e )
			{
				parent::handler( $e );
			}
		}
	}

}