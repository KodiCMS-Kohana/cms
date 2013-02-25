<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Front extends Controller_System_Controller
{
	public function action_index()
	{
		Observer::notify('frontpage_requested', array($this->request->uri()));
		
		$page = Model_Page_Front::find($this->request->uri());

		if ($page instanceof Model_Page_Front)
		{
			return $this->_render($page);
		}
		else
		{
			if(Setting::get('find_similar') == 'yes')
			{
				$uri = Model_Page_Front::find_similar($this->request->uri());
				
				if($uri !== FALSE)
				{
					HTTP::redirect($uri, 301);
				}
			}
			
			Model_Page_Front::not_found();
		}
	}
	
	private function _render($page)
	{
		Observer::notify('frontpage_found', array($page));

		// If page needs login, redirect to login
		if ($page->needs_login() == Model_Page::LOGIN_REQUIRED)
		{
			Observer::notify('frontpage_login_required', array($page));

			if (!AuthUser::isLoggedIn())
			{
				Flash::set('redirect', $page->url());

				$this->redirect(Route::get('user')->uri(array( 
					'action' => 'login'
				) ));
			}
		}


		$html = (string) $page->render_layout();

		if ( AuthUser::isLoggedIn() AND AuthUser::hasPermission(array(
			'administrator', 'developer'
		)))
		{
			$inject_html = (string) View::factory( 'system/blocks/toolbar' );
			
			// Insert system HTML before closed tag body
			$matches = preg_split('/(<\/body>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE); 
			
			if(count($matches) > 1)
			{
				/* assemble the HTML output back with the iframe code in it */
				$html = $matches[0] . $inject_html . $matches[1] . $matches[2];
			}
		}
		
		if( Kohana::$environment === Kohana::PRODUCTION )
		{
			$this->check_cache(sha1($html));
		}
		
		$this->response
			->body($html)
			->headers('last-modified', date('r', strtotime($page->updated_on)));			
			
	}
} // end class FrontController