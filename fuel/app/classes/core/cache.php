<?php
use \Fuel\Core\Cache as OriginalCache;

class Cache extends OriginalCache
{

	/**
	 * Flushes the whole cache for a specific storage driver or just a part of it when $section is set
	 * (might not work with all storage drivers), defaults to the default storage driver
	 *
	 * @param   null|string
	 * @param   null|string
	 * @return  bool
	 */
	public static function delete_all($section = null, $driver = null)
	{
		$cache = static::forge('__NOT_USED__', $driver);
		return $cache->delete_all($section);
	}

}
