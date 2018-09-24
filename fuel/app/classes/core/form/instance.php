<?php
use \Fuel\Core\Form_Instance as OriginalFormInstance;

class Form_Instance extends OriginalFormInstance {

	/**
	 * Modified Form_Instance::select() method, added posibility to use item objects instead of simple strings/arrays.
	 * item's value is set via $obj->value, rest of the object properties are set as resulting tag's (option/optgroup) attributes
	 */
	public function custom_select($field, $values = null, array $options = array(), array $attributes = array())
	{
		if (is_array($field))
		{
			$attributes = $field;

			if ( ! isset($attributes['selected']))
			{
				$attributes['selected'] = ! isset($attributes['value']) ? null : $attributes['value'];
			}
		}
		else
		{
			$attributes['name'] = (string) $field;
			$attributes['selected'] = $values;
			$attributes['options'] = $options;
		}
		unset($attributes['value']);

		if ( ! isset($attributes['options']) || ! is_array($attributes['options']))
		{
			throw new \InvalidArgumentException(sprintf('Select element "%s" is either missing the "options" or "options" is not array.', $attributes['name']));
		}
		// Get the options then unset them from the array
		$options = $attributes['options'];
		unset($attributes['options']);

		// Get the selected options then unset it from the array
		// and make sure they're all strings to avoid type conversions
		$selected = ! isset($attributes['selected']) ? array() : array_map(function($a) { return (string) $a; }, array_values((array) $attributes['selected']));

		unset($attributes['selected']);

		// workaround to access the current object context in the closure
		$current_obj =& $this;

		// closure to recusively process the options array
		$listoptions = function (array $options, $selected, $level = 1) use (&$listoptions, &$current_obj, &$attributes)
		{
			$input = PHP_EOL;
			foreach ($options as $key => $val)
			{
				if (is_object($val)) {
					$attrs	= (array) $val;
					$val	= $attrs['value'];
					unset($attrs['value']);
				} else {
					$attrs = array();
				}

				if (is_array($val))
				{
					$opt_attr = array('label' => $key , 'style' => 'text-indent: '.(10*($level-1)).'px;') + $attrs;
					$optgroup = $listoptions($val, $selected, $level + 1);
					$optgroup .= str_repeat("\t", $level);
					$input .= str_repeat("\t", $level).html_tag('optgroup', $opt_attr, $optgroup).PHP_EOL;
				}
				else
				{
					$opt_attr = array('value' => $key, 'style' => 'text-indent: '.(10*($level-1)).'px;') + $attrs;
					(in_array((string)$key, $selected, true)) && $opt_attr[] = 'selected';
					$input .= str_repeat("\t", $level);
					$opt_attr['value'] = ($current_obj->get_config('prep_value', true) && empty($attributes['dont_prep'])) ?
						$current_obj->prep_value($opt_attr['value']) : $opt_attr['value'];
					$val = ($current_obj->get_config('prep_value', true) && empty($attributes['dont_prep'])) ?
						$current_obj->prep_value($val) : $val;
					$input .= html_tag('option', $opt_attr, $val).PHP_EOL;
				}
			}
			unset($attributes['dont_prep']);

			return $input;
		};

		// generate the select options list
		$input = $listoptions($options, $selected).str_repeat("\t", 0);

		if (empty($attributes['id']) && $this->get_config('auto_id', false) == true)
		{
			$attributes['id'] = $this->get_config('auto_id_prefix', '').$attributes['name'];
		}

		return html_tag('select', $this->attr_to_string($attributes), $input);
	}

}
