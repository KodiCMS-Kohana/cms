<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Datasource
 */
class Datasource_Document extends ORM {
	
	public function filters()
	{
		return array(
			'header' => array(
				array('trim'),
			),
			'published' => array(
				array('boolval'),
			),
		);
	}

	public function rules()
	{
		return array(
			'header' => array(
				array('not_empty')
			)
		);
	}
}