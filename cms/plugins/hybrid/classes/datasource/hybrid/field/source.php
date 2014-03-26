<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class DataSource_Hybrid_Field_Source extends DataSource_Hybrid_Field {

	public function __construct( array $data = NULL )
	{
		parent::__construct( $data );
		$this->family = DataSource_Hybrid_Field::FAMILY_SOURCE;
	}
}