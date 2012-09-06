<?php

class Request extends Kohana_Request {

	public static function detect_uri()
	{
		$uri = parent::detect_uri();

		return str_replace(URL_SUFFIX, '', $uri);
	}

}