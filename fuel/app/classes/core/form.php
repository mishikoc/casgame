<?php
use \Fuel\Core\Form as OriginalForm;

class Form extends OriginalForm {

	/**
	 * Acts just like default select() but with one improvement:
	 * each item can be an object with any properties, which will be reflected in resulting options/optgroups.
	 * item's value should be in $obj->value property
	 */
	public static function custom_select($field, $values = null, array $options = array(), array $attributes = array())
	{
		return static::$instance->custom_select($field, $values, $options, $attributes);
	}

}
