<?php

use Fuel\Core\Error as OriginalError;

class Error extends OriginalError {

	public static function exception_handler(\Exception $e)
	{
		if (method_exists($e, 'handle'))
		{
			return $e->handle();
		}

		$severity = ( ! isset(static::$levels[$e->getCode()])) ? $e->getCode() : static::$levels[$e->getCode()];
		logger(\Fuel::L_ERROR, $severity.' - '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());

		if (defined('SITE_ENV') && SITE_ENV == 'prod') {
			static::show_production_error($e);
		} else {
			static::show_php_error($e);
		}
	}

	public static function error_handler($severity, $message, $filepath, $line) {
		if (error_reporting() && ($severity === E_NOTICE)) {
			return true;
		}

		return parent::error_handler($severity, $message, $filepath, $line);
	}

}
