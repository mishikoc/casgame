<?php

use Fuel\Core\Request as OriginalRequest;

class Request extends OriginalRequest {

	public function is_post() {
		return $this->get_method() === 'POST';
	}

	public function is_get() {
		return $this->get_method() === 'GET';
	}

}
