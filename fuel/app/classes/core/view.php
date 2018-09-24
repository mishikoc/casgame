<?php
use \Fuel\Core\View as OriginalView;

class View extends OriginalView {

	protected static function is_common(&$file) {
		($is_common	= (strpos($file, '__/') === 0)) and $file = str_replace('__/', '', $file);

		return $is_common;
	}

	protected static function remove_skin_name($file) {
		$file = preg_replace('#^' . SKIN_NAME . '/#', '', $file);

		return $file;
	}

	protected static function add_skin_name($file) {
		if (is_string($file)) {
			$file		= static::remove_skin_name($file);
			$is_core	= is_string(realpath(COREPATH."views/{$file}.php"));
			$is_app		= is_string(realpath(APPPATH.'views/'.SKIN_NAME."/{$file}.php"));
			$is_common	= self::is_common($file);

			(($is_core and !$is_app) or $is_common) or $file = SKIN_NAME.'/'.$file;		// don't add SKIN_NAME for core and common views
			$file = str_replace('/', DS, $file);
		}

		return $file;
	}

	public static function exists($file, $app = true) {
		$root	= ($app ? APPPATH : COREPATH).'views/';
		$file	= self::add_skin_name($file);

		return file_exists($root.$file.'.php');
	}

	public static function forge($file = null, $data = null, $auto_filter = null) {
		$file = self::add_skin_name($file);

		return parent::forge($file, $data, $auto_filter);
	}

	/**
	 * Find view file in [views/skin/path/lang/basename, views/skin/, views/]
	 * @param   string file  path
	 * @return  string|bool  correct path to view file or false if view not found
	 */
	public static function find($filename) {
		$base_dir		= APPPATH . 'views/';
		$pathinfo = pathinfo($filename);
		$search_list	= array(
			SKIN_NAME . '/' . $pathinfo['dirname'] . '/' . SITE_LANGUAGE . '/' . $pathinfo['basename'],
			SKIN_NAME . '/' . $filename,
			$filename
		);

		foreach ($search_list as $filepath) {
			$search_path = $base_dir . $filepath . '.php';

			if ($path = realpath($search_path)) {
				$view = $path;
				break;
			}
		}

		if (isset($view)) {
			$view = str_replace(array(APPPATH.'views' . DS, '.php', DS), array('', '', '/'), $view);

			if (Str::starts_with($view, SKIN_NAME)) {
				$view	= static::remove_skin_name($view);
			} else {
				$view	= '__/' . $view;
			}

			return $view;
		}

		return false;
	}

}
