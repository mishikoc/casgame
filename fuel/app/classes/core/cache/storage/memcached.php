<?php

class Cache_Storage_Memcached extends \Fuel\Core\Cache_Storage_Memcached
{

	/**
	 * Purge all caches
	 *
	 * @param   limit purge to subsection
	 * @return  bool
	 */
	public function delete_all($section)
	{
		// determine the section index name
		$section = $this->config['cache_id'].(empty($section)?'':'.'.$section);

		// get the directory index
		$index = $this->memcached->get($this->config['cache_id'].'__DIR__');

		if (is_array($index))
		{
			// limit the delete if we have a valid section
			if ( ! empty($section))
			{
				$replacements	= array(
					'.'		=> '\.',
					'*'		=> '[\w\d_-]+'
				);
				$pattern		= '#^'.str_replace(array_keys($replacements), $replacements, $section).'$#i';
				$matcher		= function ($section) use ($pattern) {
					return preg_match($pattern, $section);
				};

				$dirs = array_filter($index, $matcher);
			}
			else
			{
				$dirs = $index;
			}

			// loop through the indexes, delete all stored keys, then delete the indexes
			foreach ($dirs as $dir)
			{
				$list = $this->memcached->get($dir);
				foreach ($list as $item)
				{
					$this->memcached->delete($item[0]);
				}
				$this->memcached->delete($dir);
			}

			// update the directory index
			$index = array_diff($index, $dirs);
			$this->memcached->set($this->config['cache_id'].'__DIR__', $index);
		}
	}

}
