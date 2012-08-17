<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Search_Indexer {

	protected $_text;
	protected $_strong_tags = array(
		'strong' => '<strong>',
		'b' => '<b>'
	);
	protected $_tag_rankings = array(
		'strong' => 1,
		'b' => 2
	);

	public function __construct()
	{
		
	}

	protected function _prepare_data( $title, $content = '' )
	{
		if ( is_array( $content ) )
		{
			$content = implode( ' ', $content );
		}

		$title = UTF8::strtolower( $title );
		$content = UTF8::strtolower( $content );

		$title = $this->_get_ranked_text($title);
		$title = $this->_get_stemmed_text( $title );

		$content = $this->_get_ranked_text($content);		
		$content = $this->_get_stemmed_text( $content );

		return array( $title, $content );
	}

	public function add( $page_id, $title, $content = '', $annotation = '' )
	{
		$page_id = (int) $page_id;

		list($title, $content) = $this->_prepare_data( $title, $content );

		$result = DB::select( 'page_id' )
				->from( 'index' )
				->where( 'page_id', '=', $page_id )
				->as_object()
				->execute()
				->current();

		if ( !$result )
		{

			return DB::insert( 'index' )
				->columns( array( 'page_id', 'title', 'content', 'created_on', 'annotation' ) )
				->values( array(
					$page_id, $title, $content, date( 'Y-m-d H:i:s' ), $annotation
				) )
				->execute();
		}
		else
		{
			return $this->update($page_id, $title, $content, $annotation);
		}

		return FALSE;
	}

	public function update( $page_id, $title, $content = "", $annotation = '' )
	{
		$page_id = (int) $page_id;

		list($title, $content) = $this->_prepare_data( $title, $content );

		return DB::update( 'index' )
			->set( array(
				'title' => $title,
				'content' => $content,
				'annotation' => $annotation,
				'updated_on' => date( 'Y-m-d H:i:s' )
			) )
			->where('page_id', '=', $page_id)
			->execute();
	}

	public function remove( $page_id = NULL )
	{
		if ( !Valid::numeric( $page_id ) )
		{
			return FALSE;
		}

		$query = DB::delete( 'index' );

		if ( is_array( $page_id ) )
		{
			$query->where( 'page_id', 'in', $page_id );
		}
		else if ( $page_id === NULL )
		{
			
		}
		else
		{
			$query->where( 'page_id', '=', $page_id );
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

	protected function _get_ranked_text( $text )
	{
//		$result = "";
//		$ranks = 0;
//
//		// I should convert my array $this->strong_tags to one row with space
//		// as delimiter because of implementation of 'strip_tags' function in PHP4
//		$text = strip_tags( $text, implode( '', $this->_strong_tags ) );
//
//		Core::load( PLGPATH . 'search/vendors/htmlparser.php');
//		$parser = new HtmlParser( $text );
//
//		while ( $parser->parse() )
//		{
//			$iNodeName = strtolower( $parser->iNodeName );
//			if ( $parser->iNodeType == NODE_TYPE_ELEMENT )
//			{
//				if ( isset( $this->_tag_rankings[$iNodeName] ) )
//				{
//					$ranks += $this->_tag_rankings[$iNodeName];
//					if ( $parser->iNodeValue != '' )
//					{
//						for ( $i = 0, $l = $ranks >= BOOST_LIMIT ? BOOST_LIMIT : $ranks + 1; $i < $l; $i++ )
//						{
//							$result = $result . ' ' . $parser->iNodeValue;
//						}
//					}
//				}
//			} 
//			else if ( $parser->iNodeType == NODE_TYPE_ENDELEMENT )
//			{
//				if ( isset( $this->_tag_rankings[$iNodeName] ) )
//				{
//					$ranks -= $this->_tag_rankings[$iNodeName];
//				}
//			} 
//			else if ( $parser->iNodeType == NODE_TYPE_TEXT )
//			{
//				if ( $parser->iNodeValue != '' )
//				{
//					for ( $i = 0, $l = $ranks >= BOOST_LIMIT ? BOOST_LIMIT : $ranks + 1; $i < $l; $i++ )
//					{
//						$result = $result . ' ' . $parser->iNodeValue;
//					}
//				}
//			}
//		}
//
//		return $result;
		
		return $text;
	}
	
	protected static $instances = NULL;

	/**
	 * @return Model_Search_Indexer
	 */
	public static function instance()
	{
		if ( !isset( self::$instances ) )
		{
			self::$instances = new self;
		}

		return self::$instances;
	}

}