<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Search {

	public function __construct( &$page, $params )
	{
		$this->page = & $page;
		$this->params = $params;

		$this->page->pages = array( );
		$this->query = $this->stem_query();

		$this->search();
	}
	
	protected function stem_query()
	{
		$result = '';
		
		$search_query_key = Plugins::getSetting('search_query_key', 'search', 'q');
		$text = Arr::get($_GET, $search_query_key, '');
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

	public function search()
	{
		$columns = '`title`, `content`';
		
		$page_ids = DB::query(Database::SELECT, 'SELECT `page_id` FROM `index` WHERE MATCH(:columns) AGAINST(:query) ORDER BY updated_on DESC')
			->parameters(array(
				':query' => $this->query, 
				':columns' => DB::expr($columns)
			))
			->as_object()
			->execute()
			->as_array(NULL, 'page_id');

		foreach ( $page_ids as $id )
		{
			$page_ids[$id] = FrontPage::findById( $id );
		}

		$this->page->pages = $page_ids;
		$this->page->total_found = count($page_ids);
		$this->page->search_query = $this->query;
	}

}