<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Search {

	public function __construct( )
	{
		$this->query = $this->stem_query();
		$this->search();
	}
	
	public static function stem_query($keyword)
	{
		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Search', __FUNCTION__);
		}
		
		$result = '';

		$text = $keyword;
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

		if (isset($benchmark)) 
		{
			Profiler::stop($benchmark);
		}
		
		return $result;
	}
	
	public static function by_keyword( $keyword, $only_title = FALSE, $modules = NULL, $limit = 50, $offset = 0)
	{
		$query = self::_get_query( $keyword, $only_title, $modules, $limit, $offset )
			->select('id', 'module');
		
		$result = $query->as_object()->execute();
		
		$ids = array();

		foreach($result as $row)
		{
			$ids[$row->module][] = $row->id;
		}
		
		return $ids;
	}
	
	public static function count_by_keyword( $keyword, $only_title = FALSE, $modules = NULL )
	{
		$query = self::_get_query( $keyword, $only_title, $modules, NULL )
			->select(array(DB::expr('COUNT(*)'), 'total'));
		
		return $query
			->execute()
			->get('total');
	}
	
	protected static function _get_query( $keyword, $only_title = FALSE, $modules = NULL, $limit = 50, $offset = 0 )
	{
		$keyword = Search::stem_query($keyword);
		
		$words = explode(' ', $keyword);

		$query = DB::select()
			->from('search_index');
		
		if($limit !== NULL)
		{
			$query
				->limit((int) $limit)
				->offset($offset);
		}
		
		if(is_array($modules))
		{
			$query->where('module', 'in', $modules);
		}
		elseif ($modules !== NULL) 
		{
			$query->where('module', '=', $modules);
		}
		
		$query->where_open();
			$query->where_open();

			foreach ($words as $word )
			{
				if(!empty($word))
					$query->where('title', 'like', '%'.$word.'%');
			}

			$query->where_close();

			if($only_title === FALSE)
			{
				$query->or_where_open();
				foreach ($words as $word )
				{
					if(!empty($word))
						$query->where('content', 'like', '%'.$word.'%');
				}
				$query->where_close();
			}
		$query->where_close();
		
		return $query;
	}

	public static function add_to_index( $module, $id, $title, $content = '' ) 
	{
		$indexer = new Model_Search_Indexer;
		return $indexer->add($module, $id, $title, $content);
	}
}