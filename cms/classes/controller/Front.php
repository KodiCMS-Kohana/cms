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
			$page->render_layout();
		}
		else
		{
			page_not_found();
		}
	}
} // end class FrontController