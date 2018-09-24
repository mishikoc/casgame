<?php

use Fuel\Core\Config as OriginalConfig;

class Config extends OriginalConfig {

	/**
	 * Fuck fuel economy.
	 * And again. $group = true says to set group equal to file name.
	 * But this only works one time. When you call ::load() second time you must set both args to strings, using ::load('file', true) won't work.
	 * WTF??
	 */
	public static function load($file, $group = null, $reload = false, $overwrite = false) {
		if ($reload) {
			return parent::load($file, $group, $reload, $overwrite);
		}

		return parent::load($file, ($group === true) ? $file : $group, $reload, $overwrite);
	}

}
