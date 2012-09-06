<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_File_Snippet extends Model_File {

	public function __construct( $name = '' )
	{
		$this->_path = SNIPPETS_SYSPATH;
		parent::__construct( $name );
	}
}