<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_HTML extends DataSource_Hybrid_Field_Primitive {

	protected $_is_indexable = FALSE;

	protected $_props = array(
		'default' => NULL,
		'filter_html' => FALSE,
		'remove_empty_tags' => FALSE,
		'allowed_tags' => '<b><i><u><p><ul><li><ol>'
	);

	public function booleans()
	{
		return array('filter_html', 'remove_empty_tags');
	}
	
	public function onSetValue($value, DataSource_Hybrid_Document $doc)
	{
		$value = parent::onSetValue($value, $doc);
		
		if ($this->remove_empty_tags === TRUE)
		{
			return Kses::remove_empty_tags($value);
		}

		return $value;
	}

	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		if ($this->filter_html === TRUE)
		{
			$new->set($this->name, Kses::filter($new->get($this->name), $this->allowed_tags));
		}
	}

	public function get_type()
	{
		return 'TEXT NOT NULL';
	}

	public function fetch_headline_value($value, $document_id)
	{
		return Text::limit_words($value, 50);
	}
}