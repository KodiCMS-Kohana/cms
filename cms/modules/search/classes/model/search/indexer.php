<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Search_Indexer {

	protected $_text;

	protected function _prepare_data( $title, $content = '' )
	{
		if ( is_array( $content ) )
		{
			$content = implode( ' ', $content );
		}
		
		if ( is_array( $title ) )
		{
			$title = implode( ' ', $title );
		}

		$title = UTF8::strtolower( $title );
		$content = UTF8::strtolower( $content );

		$title = $this->_get_stemmed_text( $title );
		$content = $this->_get_stemmed_text( $content );

		return array( $title, $content );
	}

	public function add( $module, $id, $title, $content = '', $params = array() )
	{
		$id = (int) $id;

		list($title, $content) = $this->_prepare_data( $title, $content );

		$result = DB::select( 'id' )
				->from( 'search_index' )
				->where( 'module', '=', $module )
				->where( 'id', '=', $id )
				->as_object()
				->execute()
				->current();

		if ( !$result )
		{
			return DB::insert( 'search_index' )
				->columns( array( 'module', 'id', 'title', 'content', 'created_on', 'params' ) )
				->values( array(
					$module, $id, $title, $content, date( 'Y-m-d H:i:s' ), serialize($params)
				) )
				->execute();
		}
		else
		{
			return $this->update($module, $id, $title, $content, $params);
		}

		return FALSE;
	}

	public function update( $module, $id, $title, $content = "", $params = array() )
	{
		$id = (int) $id;

		list($title, $content) = $this->_prepare_data( $title, $content );

		return DB::update( 'search_index' )
			->set( array(
				'title' => $title,
				'content' => $content,
				'updated_on' => date( 'Y-m-d H:i:s' ),
				'params' => serialize($params)
			) )
			->where( 'module', '=', $module )
			->where( 'id', '=', $id )
			->execute();
	}

	public function remove( $module, $id = NULL )
	{
		if ( !Valid::numeric( $id ) )
		{
			return FALSE;
		}

		$query = DB::delete( 'search_index' )
			->where( 'module', '=', $module );

		if ( is_array( $id ) )
		{
			$query->where( 'id', 'in', $id );
		}
		else if ( $id === NULL )
		{
			
		}
		else
		{
			$query->where( 'id', '=', $id );
		}

		return $query->execute();
	}

	protected function _get_stemmed_text( $text )
	{
		$result = '';

		$text = strip_tags( $text );

		$stop_words = Model_Search_Stopwords::get();

		// Parse original text and stem all words that are not tags
		$tkn = new Model_Search_Tokenizer();
		$tkn->set_text( $text );
		$tkn->stopwords = $stop_words;
		
		$stemmer = new Model_Search_Stemmer($tkn);

		while ( $cur = $tkn->next() )
		{
			$result .= $stemmer->stem($cur);
		}

		return $result;
	}
}