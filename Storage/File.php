<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Cache\Storage;

use RuntimeException;
use Opis\Cache\AbstractStorage;

class FileStorage extends AbstractStorage
{

	/**
	 * Cache path.
	 *
	 * @var string
	 */

	protected $path;

	/**
	 * Constructor.
	 *
	 * @access  public
	 * @param	string	$identifier Identifier
	 * @param	string	$path	Path
	 */

	public function __construct($identifier, $path)
	{
		parent::__construct($identifier);
		
		$this->path = trim($path, '/');
		
		if(file_exists($this->path) === false || is_readable($this->path) === false || is_writable($this->path) === false)
		{
			throw new RuntimeException(vsprintf("%s(): Cache directory ('%s') is not writable.", array(__METHOD__, $this->path)));
		}
	}


	/**
	 * Returns the path to the cache file.
	 * 
	 * @access  public
	 * @param   string  $key  Cache key
	 * @return  string
	 */

	protected function cacheFile($key)
	{
		return $this->path . '/' . $this->identifier . $key . '.cache';
	}

	/**
	 * Store variable in the cache.
	 *
	 * @access  public
	 * @param   string   $key    Cache key
	 * @param   mixed    $value  The variable to store
	 * @param   int      $ttl    (optional) Time to live
	 * @return  boolean
	 */

	public function write($key, $value, $ttl = 0)
	{
		$ttl = (((int) $ttl === 0) ? 31556926 : (int) $ttl) + time();

		$data = "{$ttl}\n" . serialize($value);

		return is_int(file_put_contents($this->cacheFile($key), $data, LOCK_EX));
	}

	/**
	 * Fetch variable from the cache.
	 *
	 * @access  public
	 * @param   string  $key  Cache key
	 * @return  mixed
	 */

	public function read($key)
	{
		if(file_exists($this->cacheFile($key)))
		{
			// Cache exists
			
			$handle = fopen($this->cacheFile($key), 'r');

			if(time() < (int) trim(fgets($handle)))
			{
				$cache = '';

				while(!feof($handle))
				{
					$cache .= fgets($handle);
				}

				fclose($handle);

				return unserialize($cache);
			}
			else
			{

				fclose($handle);

				unlink($this->cacheFile($key));

				return false;
			}
		}
		return false;
	}

	/**
	 * Returns TRUE if the cache key exists and FALSE if not.
	 * 
	 * @access  public
	 * @param   string   $key  Cache key
	 * @return  boolean
	 */

	public function has($key)
	{
		if(file_exists($this->cacheFile($key)))
		{
			$handle = fopen($this->cacheFile($key), 'r');
			
			$expired = (time() < (int) trim(fgets($handle)));
			
			fclose($handle);
			
			return $expired;
		}

		return false;
	}


	/**
	 * Delete a variable from the cache.
	 *
	 * @access  public
	 * @param   string   $key  Cache key
	 * @return  boolean
	 */

	public function delete($key)
	{
		if(file_exists($this->cacheFile($key)))
		{
			return unlink($this->cacheFile($key));
		}

		return false;
	}

	/**
	 * Clears the user cache.
	 *
	 * @access  public
	 * @return  boolean
	 */

	public function clear()
	{
		$files = scandir($this->path);
		
		if($files !== false)
		{
			foreach($files as $file)
			{
				$lenght = mb_strlen($file);
				if($lenght > 6 && mb_substr($lenght - 6, 6) === '.cache')
				{
					if(unlink($this->path . '/' . $file) === false)
					{
						return false;
					}
				}
			}
		}

		return true;
	}
}