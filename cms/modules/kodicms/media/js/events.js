var settings = {
	main_menu: {
		detect_active_predicate: function(href, url) {
			console.log(href == url);
			
			if(href == url)
				return true;
			else if(BASE_URL == href && href == url)
				return true;
			else if(BASE_URL != href && url.indexOf(href) != -1)
				return true;
//			else if(url.indexOf(href) != -1)
//				return true;

			return false;
		}
	}
}

window.PixelAdmin.start(null, settings);
