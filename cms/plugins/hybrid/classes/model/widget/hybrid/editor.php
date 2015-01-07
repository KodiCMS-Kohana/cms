<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Hybrid_Editor extends Model_Widget_Hybrid_Document {

	protected $_use_caching = FALSE;
	
	/**
	 *
	 * @var string 
	 */
	public $doc_id_field = 'd.id';

	/**
	 * 
	 * @return array [$fields, $datasource, $document]
	 */
	public function fetch_data()
	{
		$datasource = Datasource_Data_Manager::load($this->ds_id);

		if ($datasource === NULL)
		{
			return array();
		}

		$id = $this->get_doc_id();

		if (empty($id))
		{
			$document = $datasource->get_empty_document();
		}
		else
		{
			$document = $datasource->get_document($id);

			if (!$document)
			{
				if ($this->throw_404)
				{
					$this->_ctx->throw_404();
				}

				$document = $datasource->get_empty_document();
			}
		}

		View::set_global(array(
			'form' => array(
				'label_class' => 'control-label col-md-2 col-sm-3',
				'input_container_class' => 'col-md-10 col-lg-10 col-sm-9',
				'input_container_offset_class' => 'col-md-offset-2 col-sm-offset-3 col-md-10 col-sm-9'
			)
		));
		
		return array(
			'fields' => $datasource->record()->fields(),
			'datasource' => $datasource,
			'document' => $document
		);
	}
}