<?php
use \Fuel\Core\Html as OriginalHTML;


class Html extends OriginalHTML {

	/**
	 * Overriden anchor. Accepts both string and array $href arg.
	 * array version should have next format: array('route_name', 'arg1' => 'val1', 'arg2' => 'val2')
	 *
	 * @param	mixed	the url
	 * @param	string	the text value
	 * @param	array	the attributes array
	 * @param	bool	true to force https, false to force http
	 * @return	string	the html link
	 */
	public static function anchor($href, $text = null, $attr = array(), $secure = null) {
		if (is_array($href)) {
			$route_name	= array_shift($href);
			$href		= Router::get($route_name, $href);
		}

		return parent::anchor($href, $text, $attr, $secure);
	}

	public static function items($items, $callback, $delimiter = ', ') {
		if (empty($items)) {
			return;
		}

		$index	= 0;
		$last	= array_pop($items);
		foreach ($items as $item) {
			$callback($item, $index);
			echo $delimiter;
			$index++;
		}

		$callback($last, $index);
	}

	/**
	 * @param array items that should be rendered as a tree
	 * @param mixed selected item (array with 'alias' index) or an empty value if none of them should be selected
	 * @param callback function that returns valid route (or its config). current item is passed as an argument
	 * @todo add callbacks for folder and file active checking
	 */
	public static function treeview($items, $selected_item, $route_callback, $level = 1) {
		foreach ($items as $item) {
			$class = '';
			if (!empty($selected_item)) {
				($item['alias'] == $selected_item['alias']) and $class = 'active ';
			} elseif (isset($item['active'])) {
				$class = 'active ';
			}

			if (isset($item['children'])) {
				echo '<li>';
				$class	.= 'folder';
				// $active	= !empty($selected_item) && ($item['alias'] == $selected_item['alias']);
				// $active and $class .= ' active';
				echo html_tag('span', array('class' => $class), $item['name']);
				echo '<ul>';
				static::treeview($item['children'], $selected_item, $route_callback, $level + 1);
				echo '</ul>';
				echo '</li>';
			} else {
				$class	.= 'file';
				// $active	= !empty($selected_item) && ($item['alias'] == $selected_item['alias']);
				// $active and $class .= ' active';

				$attributes = array(
					'class'		=> 'link',
					'url'		=> Uri::create($route_callback($item)),
				);
				$span = html_tag('span', array('class' => $class), $item['name']);

				echo html_tag('li', $attributes, $span);
			}
		}
	}

	/**
	 * Overridden build_list to allow using array of objects as a source list
	 * to specify any desired attributes for generated li tags
	 *
	 * @param	string	list type (ol or ul)
	 * @param	array	list items, may be nested
	 * @param	array	tag attributes
	 * @param	string	indentation
	 * @return	string
	 */
	protected static function build_list($type = 'ul', array $list = array(), $attr = false, $indent = '')
	{
		if ( ! is_array($list))
		{
			$result = false;
		}

		$out = '';
		foreach ($list as $key => $val)
		{
			if (is_object($val)) {
				$attrs	= (array) $val;
				$val	= $attrs['value'];
				unset($attrs['value']);
			} else {
				$attrs = false;
			}

			if ( ! is_array($val))
			{
				$out .= $indent."\t".html_tag('li', $attrs, $val).PHP_EOL;
			}
			else
			{
				$out .= $indent."\t".html_tag('li', $attrs, $key.PHP_EOL.static::build_list($type, $val, '', $indent."\t\t").$indent."\t").PHP_EOL;
			}
		}
		$result = $indent.html_tag($type, $attr, PHP_EOL.$out.$indent).PHP_EOL;
		return $result;
	}

}
