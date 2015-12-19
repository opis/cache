<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2015 Marius Sarca
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

use \Memcached as PHP_Memcached;
use Opis\Cache\StorageInterface;

class Memcached implements StorageInterface
{
    /** @var    \Memcached  Memcached object. */
    protected $memcached;

    /** @var    string */
    protected $prefix;

    /**
     * Constructor.
     *
     * @access  public
     * @param	\Memcahed	$memcached 	Memcached instance
     * @param   string      $prefix     (optional) Cache key prefix
     * @param	boolean		$compress	(optional) Compress
     * @param	int			$timeout	(optional) Timeout seconds
     */
    public function __construct(PHP_Memcached $memcached, $prefix = '', $compress = true, $timeout = 1)
    {

        $this->memcached = $memcached;
        $this->prefix = $prefix;

        if ($timeout !== 1) {
            $this->memcached->setOption(PHP_Memcached::OPT_CONNECT_TIMEOUT, ($timeout * 1000));
        }

        if ($compress === false) {
            $this->memcached->setOption(PHP_Memcached::OPT_COMPRESSION, false);
        }
    }

    /**
     * Destructor.
     *
     */
    public function __destruct()
    {
        $this->memcached = null;
    }

    /**
     * Store variable in the cache.
     *
     * @param   string   $key    Cache key
     * @param   mixed    $value  The variable to store
     * @param   int      $ttl    (optional) Time to live
     * 
     * @return  boolean
     */
    public function write($key, $value, $ttl = 0)
    {
        if ($ttl !== 0) {
            $ttl += time();
        }

        if ($this->memcached->replace($this->prefix . $key, $value, $ttl) === false) {
            return $this->memcached->set($this->prefix . $key, $value, $ttl);
        }

        return true;
    }

    /**
     * Fetch variable from the cache.
     *
     * @param   string  $key  Cache key
     * 
     * @return  mixed
     */
    public function read($key)
    {
        return $this->memcached->get($this->prefix . $key);
    }

    /**
     * Returns TRUE if the cache key exists and FALSE if not.
     * 
     * @param   string   $key  Cache key
     * 
     * @return  boolean
     */
    public function has($key)
    {
        return ($this->memcached->get($this->prefix . $key) !== false);
    }

    /**
     * Delete a variable from the cache.
     *
     * @param   string   $key  Cache key
     * 
     * @return  boolean
     */
    public function delete($key)
    {
        return $this->memcached->delete($this->prefix . $key, 0);
    }

    /**
     * Clears the user cache.
     *
     * @return  boolean
     */
    public function clear()
    {
        return $this->memcached->flush();
    }
}
