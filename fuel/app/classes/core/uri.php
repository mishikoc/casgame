<?php
use \Fuel\Core\Uri as OriginalUri;


class Uri extends OriginalUri {

	/**
	 * Creates a url with the given uri, including the base url
	 *
	 * @param   string  $uri            The uri to create the URL for
	 * @param   array   $variables      Some variables for the URL
	 * @param   array   $get_variables  Any GET urls to append via a query string
	 * @param   bool    $secure         If false, force http. If true, force https
	 * @return  string
	 */
	public static function create($uri = null, $variables = array(), $get_variables = array(), $secure = null) {
		$url = parent::create($uri, $variables, $get_variables, $secure);

		if ($secure && Config::get('front.short_secure_url')) {
			$url = str_replace('www.', '', $url);
		} elseif (!$secure && Input::https()) {
			$url = str_replace('https://', 'http://', $url);
		}

		return $url;
	}

}
