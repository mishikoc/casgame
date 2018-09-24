<?php
use Fuel\Core\Router as OriginalRouter;

/**
 * Extended original Router class
 *
 * @author		SlKelevro & Fuel Development Team
 */

class Router extends OriginalRouter
{

	/**
	 * @var Route the route which parsed current request
	 */
	public static $active_route;

	/**
	 * @var Route the route which is currently checking the request
	 */
	public static $current_route;

	public static $patterns = array(
			':wnumdash'		=> '[\w\d-]+?',
			':any'			=> '.+',
			':alnum'		=> '[[:alnum:]]+',
			':num'			=> '\d+',
			':alpha'		=> '[[:alpha:]]+',
			':segment'		=> '[^/]*',
            ':aldash'	    => '[[:alpha:]-]+',
	);

	/**
	 * Add one or multiple routes
	 *
	 * @param  string
	 * @param  string|array|Route  either the translation for $path, an array for verb routing or an instance of Route
	 * @param  bool                whether to prepend the route(s) to the routes array
	 */
	public static function add($path, $options = null, $prepend = false, $case_sensitive = null)
	{
		if (is_array($path))
		{
			// Reverse to keep correct order in prepending
			$prepend and $path = array_reverse($path, true);
			foreach ($path as $p => $t)
			{
				static::add($p, $t, $prepend);
			}
			return;
		}
		elseif ($options instanceof Route)
		{
			static::$routes[isset($options->name) ? $options->name : $path] = $options;
			return;
		}

		$name = $path;
		if (is_array($options) and array_key_exists('name', $options))
		{
			$name = $options['name'];
			unset($options['name']);
			if (count($options) == 1 and ! is_array($options[0]))
			{
				$options = $options[0];
			}
		}

		if ($prepend)
		{
			\Arr::prepend(static::$routes, $name, new \Route($path, $options, $case_sensitive));
			return;
		}

		static::$routes[$name] = new \Route($path, $options, $case_sensitive);
	}

	/**
	 * Does reverse routing for a named route.  This will return the FULL url
	 * (including the base url and index.php).
	 *
	 * WARNING: This is VERY limited at this point.  Does not work if there is
	 * any regex in the route.
	 *
	 * Usage:
	 *
	 * <a href="<?php echo Router::get('foo'); ?>">Foo</a>
	 *
	 * @param   string  $name  the name of the route
	 * @param   array   $named_params  the array of named parameters
	 * @return  string  the full url for the named route
	 */
	public static function get($name, $named_params = array())
	{

		if (!isset(static::$routes[$name])) {
			return '';
		}

		if (static::$routes[$name]->readonly) {
			return '#';
		}

		$path	= static::$routes[$name]->path;
		$walker	= function ($value, $name) use (&$path) {
			$path = preg_replace("#:{$name}[;\b]#", $value, $path);
		};

		array_walk($named_params, $walker);
		unset($walker);

		return $path;
	}

	/**
	 * Prepare a route if don't have all of the required named parameters.
	 * Works the same as Router::get() (in fact, uses it)
	 * @param string route name
	 * @param array named route parameters
	 * @return mixed
	 * 			- string if all parameters passed
	 * 			- PreparedRoute object if some parameters are missing
	 */
	public static function prepare($name, $named_params = array()) {
		$prepared_path = static::get($name, $named_params);

		if (preg_match('#:\w+[;\b]#', $prepared_path)) {
			return new PreparedRoute($prepared_path);
		}

		return $prepared_path;
	}

	public static function delete($path, $case_sensitive = null) {
		$path = str_replace(array_keys(static::$patterns), static::$patterns, $path);

		parent::delete($path, $case_sensitive);
	}

	protected static function check_redirects($request) {
		// there can be other extensions in URL
		// but we only need to check .html and non-ajax requests
		$uri = Input::uri();
		if (!Input::is_ajax() && ltrim($uri, '/') !== '') {
			$no_extension	= is_null(Input::extension());
			$suffix			= Config::get('url_suffix', '') !== '';
			$add_suffix		= $suffix && $no_extension;
			$remove_suffix	= !$no_extension && !$suffix;	// add to condition if required
			$trailing_slash	= substr($uri, -1) === '/';

			// don't do anything if an existing folder is accessed (e.g. /assets/)
			if (!realpath(DOCROOT . $uri)) {
				if ($trailing_slash) {
					$redirect_url = Uri::create(rtrim($uri, '/'));
				} elseif ($add_suffix /* || $remove_suffix */) {
					$redirect_url = Uri::create($uri);
				}
			}

			isset($redirect_url) and Response::redirect($redirect_url, 'location', 301);
		}

		$uri		= $request->uri->get();
		$redirects	= Config::get('routing.redirects', array());

		foreach ($redirects as $code => $signals) {
			foreach ($signals as $search => $replacement) {
				if (is_int(strpos($uri, $search))) {
					$status	= $code;
					$uri	= str_replace($search, $replacement, $uri);
				}
			}

			if (isset($status)) {
				break;
			}
		}

		isset($status) and Response::redirect($uri, 'location', $status);
	}

	/**
	 * Processes the given request using the defined routes
	 *
	 * @param	Request		the given Request object
	 * @param	bool		whether to use the defined routes or not
	 * @return	mixed		the match array or false
	 */
	public static function process(\Request $request, $route = true)
	{
		static::check_redirects($request);

		$match = false;

		if ($route)
		{
			foreach (static::$routes as $route)
			{
				if ($match = $route->parse($request))
				{
					break;
				}
			}
		}

		if ( ! $match)
		{
			// Since we didn't find a match, we will create a new route.
			$match = new \Routes\Base(array(preg_quote($request->uri->get(), '#'), $request->uri->get(), '__noname'));
			$match->parse($request);
		}

		if ($match->callable !== null)
		{
			return $match;
		}

		return static::parse_match($match);
	}

	/**
	 * Find the controller that matches the route requested
	 *
	 * @param	Route  $match  the given Route object
	 * @return	mixed  the match array or false
	 */
	protected static function parse_match($match)
	{
		static::$active_route = $match;

		return parent::parse_match($match);
	}

}

/**
 * Prepared route class. Contains a not-ready-for-url route,
 * which has some of its arguments, but not all.
 */
class PreparedRoute {

	/**
	 * @var string incomplete route path to be compiled
	 * (or complete, if compile() method was called with true as 2nd argument)
	 */
	protected $path;

	/**
	 * @var bool flag, checked when compiled route is saved to object's $path property
	 */
	protected $saved = false;

	public function __construct($path) {
		$this->path = $path;
	}

	/**
	 * Compiles itself with specified named parameters
	 * @param array named parameters
	 * @param bool true if compiled route should be saved in object property
	 * @return string ready for using in urls route
	 */
	public function compile($named_params, $save = false) {
		if ($this->saved) {
			return $this->path;
		}

		$path	= $this->path;
		$walker	= function ($value, $name) use (&$path) {
			$path = preg_replace("#:{$name}[;\b]#", $value, $path);
		};

		array_walk($named_params, $walker);
		unset($walker);

		$this->saved = $save and $this->path = $path;

		return $path;
	}

}
