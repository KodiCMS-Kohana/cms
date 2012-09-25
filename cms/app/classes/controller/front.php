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
			echo $page->render_layout();
		
			if ( Setting::get( 'profiling' ) == 'yes' AND AuthUser::isLoggedIn())
			{
				echo View::factory( 'profiler/stats' );
			}
		}
		else
		{
			FrontPage::not_found();
		}
	}

} // end class FrontController