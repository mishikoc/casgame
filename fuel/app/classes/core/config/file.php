<?php
use \Fuel\Core\Config_File as OriginalConfig_File;

abstract class Config_File extends OriginalConfig_File {

	/**
	 * Finds the given config files
	 *
	 * @param   bool  $multiple  Whether to load multiple files or not
	 * @return  array
	 */
	protected function find_file($cache = true)
	{
		$paths = \Finder::search('config', $this->file, $this->ext, true);

		if (!defined('SKIN_NAME')) {
			return $paths;
		}

		// absolute path requested?
		if ($this->file[0] === '/' or (isset($this->file[1]) and $this->file[1] === ':'))
		{
			// don't search further, load only the requested file
			return $paths;
		}

		$paths = array_merge(\Finder::search('config/'.SITE_ENV, $this->file, $this->ext, true), $paths);
		$paths = array_merge(\Finder::search('config/'.SKIN_NAME, $this->file, $this->ext, true), $paths);
		$paths = array_merge(\Finder::search('config/'.SKIN_NAME.'/'.SITE_ENV, $this->file, $this->ext, true), $paths);
		$paths = array_merge(\Finder::search('config/'.SKIN_NAME.'/'.SITE_LANGUAGE, $this->file, $this->ext, true), $paths);
		$paths = array_merge(\Finder::search('config/'.\Fuel::$env, $this->file, $this->ext, true), $paths);

		if (count($paths) > 0)
		{
			return array_reverse($paths);
		}

		throw new \ConfigException(sprintf('File "%s" does not exist.', $this->file));
	}

}
