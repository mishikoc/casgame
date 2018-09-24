<?php

use Fuel\Core\Profiler as OriginalProfiler;

class Profiler extends OriginalProfiler {

	/**
	 * Sort queries by type (INSERT -> UPDATE -> DELETE -> SELECT) and query time
	 */
	public static function output() {
		if (static::$profiler) {
			$types = array('INSERT', 'UPDATE', 'DELETE', 'SELECT');

			usort(static::$profiler->queries, function ($first, $second) use ($types) {
				$firstPos			= array_search(substr($first['sql'], 0, 6), $types);
				$secondPos			= array_search(substr($second['sql'], 0, 6), $types);
				$registeredTypes	= is_int($firstPos) && is_int($secondPos);

				if ($registeredTypes && ($diff = ($firstPos - $secondPos))) {
					return $diff;
				}

				$diff = $first['time'] - $second['time'];

				return $diff ? ($diff > 0 ? -1 : 1) : 0;	// we need DESC sort
			});
		}

		return parent::output();
	}

}
