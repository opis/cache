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

use Opis\Cache\StorageInterface;

class Proxy implements StorageInterface
{

	/** @var    array   Cached data. */
	protected $cache = array();
	
	/** @var    StorageInterface    Proxy. */
	protected $proxy;
    
    
	public function __construct(StorageInterface $proxy)
	{
		$this->proxy = $proxy;
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
		
		if($this->proxy->write($key, $value, $ttl))
		{
			$this->cache[$key] = $value;
			return true;
		}
		
		return false;
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
		if(!isset($this->cache[$key]))
		{
			return $this->cache[$key]  = $this->proxy->read($key);
		}
		
		return $this->cache[$key];
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
		return (isset($this->cache[$key]) || $this->proxy->has($key));
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
		if($this->proxy->delete($key))
		{
			unset($this->cache[$key]);
			return true;
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
		if($this->proxy->clear())
		{
			$this->cache = array();
			return true;
		}
		return false;
	}
}
