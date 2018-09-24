<?php
use Fuel\Core\Input as OriginalInput;

class Input extends OriginalInput {

	/**
	 * You can't rely on anybody, have to do everything by yourself (c)
	 * Couldn't fuel developers think of this easy must-have function by themselves? Shame on them! xD
	 *
	 * @return bool true if we are secured
	 */
	public static function https() {
		return (static::protocol() === 'https');
	}

	/**
	 * Adding a fix for Windows (trim '\' at the beginning).
	 */
	public static function uri() {
		return static::$detected_uri = trim(parent::uri(), '\\');
	}

}

