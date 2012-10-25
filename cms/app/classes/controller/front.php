<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Front extends Controller
{
	public $auto_render = FALSE;

	public function action_index()
	{
		Observer::notify('frontpage_requested', array($this->request->uri()));
		
		$page = FrontPage::find($this->request->uri());
		
		// if we fund it, display it!
		if ($page !== false && $page !== null)
		{
			return $this->_render($page);
		}
		else
		{
			if(Setting::get('find_similar') == 'yes')
			{
				$page = FrontPage::find_similar($this->request->uri());
				if($page instanceof FrontPage)
				{
					HTTP::redirect($page->url(), 301);
				}
			}
			
			FrontPage::not_found();
		}
	}
	
	private function _render($page)
	{
		// If page needs login, redirect to login
		if ($page->getLoginNeeded() == FrontPage::LOGIN_REQUIRED)
		{
			AuthUser::load();

			if (!AuthUser::isLoggedIn())
			{
				Flash::set('redirect', $page->url());

				$this->go_backend();
			}
		}

		Observer::notify('frontpage_found', array($page));

		$html = (string) $page->render_layout();
		

		if ( AuthUser::isLoggedIn())
		{
			$inject_html = (string) View::factory( 'system/blocks/toolbar' );
			
			if(Setting::get( 'profiling' ) == 'yes')
			{
				$inject_html .= (string) View::factory( 'profiler/stats' );
			}
			
			// Insert system HTML before closed tag body
			$matches = preg_split('/(<\/body>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE); 
			/* assemble the HTML output back with the iframe code in it */
			$html = $matches[0] . $inject_html . $matches[1] . $matches[2];
		}
		
		echo Response::factory()
			->body($html);
	}
} // end class FrontController