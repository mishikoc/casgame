<?php

use Fuel\Core\Arr as OriginalArr;

class Arr extends OriginalArr {

	/**
	 * Filter empty values in array
	 * @param array filter target
	 * @return array array, filtered from empty values
	 */
	public static function filter_empty($array) {
		$filter = function ($value) {
			return !empty($value);
		};

		$array = array_filter($array, $filter);
		unset($filter);

		return $array;
	}

	/**
	 * Maintains/applies custom order of data
	 *
	 * @param array data indexed by key, which is the sort condition
	 * @param array sort key values in desired order
	 * @return array sorted data
	 */
	public static function custom_order($data, $keys)
	{
		$result = array();

		foreach ($keys as $key) {
			$result[$key] = $data[$key];
		}

		return $result;
	}

	public static function key_array($key, $array)
	{
		$output = array();

		foreach ($array as $item) {
			$output[$item[$key]] = $item;
		}

		return $output;
	}

	public static function value_array($key, $array)
	{
		$output = array();

		foreach ($array as $item) {
			$output[] = $item[$key];
		}

		return $output;
	}

	public static function key_value($key, $value, $array)
	{
		$output = array();

		foreach ($array as $item) {
			$output[$item[$key]] = $item[$value];
		}

		return $output;
	}

}
