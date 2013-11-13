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

use \Memcache as PHP_Memcache;
use RuntimeException;
use Opis\Cache\AbstractStorage;

class Memcache extends AbstractStorage
{

	/** @var	\Memcache	Memcache object. */
	protected $memcache;

	/** @var	int	Compression level. */
	protected $compression = 0;

	/**
	 * Constructor.
	 *
	 * @access  public
	 * @param   string	$identifier	Cache identifier
	 * @param	\Memcache	$memcache	Memcache instance
	 * @param	boolean	$compress	Compress data
	 */

	public function __construct($identifier, PHP_Memcache $memcache, $compress = true)
	{
		parent::__construct($identifier);

		$this->memcache = $memcache;

		if($compress !== false)
		{
			$this->compression = MEMCACHE_COMPRESSED;
		}
	}

	/**
	 * Destructor.
	 *
	 * @access  public
	 */

	public function __destruct()
	{
		if($this->memcache !== null)
		{
			$this->memcache->close();
		}
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
		if($ttl !== 0)
		{
			$ttl += time();
		}
		
		if($this->memcache->replace($this->identifier . $key, $value, $this->compression, $ttl) === false)
		{
			return $this->memcache->set($this->identifier . $key, $value, $this->compression, $ttl);
		}
		
		return true;
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
		return $this->memcache->get($this->identifier . $key);
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
		return ($this->memcache->get($this->identifier . $key) !== false);
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
		return $this->memcache->delete($this->identifier . $key, 0);
	}

	/**
	 * Clears the user cache.
	 *
	 * @access  public
	 * @return  boolean
	 */

	public function clear()
	{
		return $this->memcache->flush();
	}
}