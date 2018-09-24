<?php
use \Fuel\Core\ViewModel as OriginalViewModel;

class ViewModel extends OriginalViewModel {

	public static function forge($viewmodel, $method = 'view', $auto_filter = null) {
		$is_common	= (strpos($viewmodel, '__/') === 0) and $viewmodel = str_replace('__/', '', $viewmodel);
		$is_common or $viewmodel = SKIN_NAME.'/'.trim($viewmodel, '/');
		// $viewmodel = SKIN_NAME.'/'.trim($viewmodel, '/');

		return parent::forge($viewmodel, $method, $auto_filter);
	}

	/**
	 * Overriden set. Allows passing an array of values
	 */
	public function set($key, $value = null, $filter = null) {
		return parent::set($key, $value, $filter);
	}

	/**
	 * Fuck fuel economy.
	 * Yet another tiny, obvious piece of code that FuelPHP team could have written by itself.
	 * Allows to use set_safe in ViewModel
	 */
	public function set_safe($key, $value = null) {
		return $this->_view->set($key, $value, false);
	}

	/**
	 * Shortcut for changing assigned view
	 */
	protected function change_view($view) {
		$this->_view = $view;
		$this->set_view();
	}

}

