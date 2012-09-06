<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_File_Layout extends Model_File {

	public function __construct( $name = '' )
	{
		$this->_path = LAYOUTS_SYSPATH;
		parent::__construct( $name );
	}
	
	public function is_used()
    {
        return Record::countFrom('Page', 'layout_file = :name', array(
			':name' => $this->name
		));
    }
}