<?php
use Fuel\Core\Asset as OriginalAsset;

class Asset extends OriginalAsset {

	/**
	 * Get language-level version of a filename (with _%lang% added)
	 * @param string filename
	 * @return string filename with language
	 */
	public static function lang_version($file) {
		$pathinfo = pathinfo($file);
		$filename = trim($pathinfo['dirname'].'/'.$pathinfo['filename'].'_'.SITE_LANGUAGE.'.'.$pathinfo['extension'], './');

		return $filename;
	}

	/**
	 * Get both global and lang-level images with the same filenames.
	 * It's almost an alias to Asset::img(), just adds a language version of a file for each passed image
	 * @param mixed image(s) filename(s) [string/array]
	 * @param array image attributes
	 * @param string asset group name
	 * @return string image tags
	 */
	public static function lang_img($images = array(), $attr = array(), $group = NULL) {
		is_array($images) or $images = array($images);
		$result = '';

		foreach ($images as $image) {
			$img = static::img(static::lang_version($image), $attr, $group);
			empty($img) and $img = static::img($image, $attr, $group);

			$result .= $img;
		}

		return $result;
	}

	/**
	 * Wrapper for Asset::get_file(), works the same as ::lang_img():
	 * checks if lang version exists; if not - gets original one.
	 * @param	string	The filename to locate
	 * @param	string	The file type (img/js/css)
	 * @param	string	The sub-folder to look in (optional)
	 * @return	mixed	Either the path to the file or false if not found
	 */
	public static function get_lang_file($file, $type, $folder = '') {
		$file_path = parent::get_file(static::lang_version($file), $type, $folder);
		empty($file_path) and $file_path = parent::get_file($file, $type, $folder);

		return $file_path;
	}

}
