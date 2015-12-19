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

namespace Opis\Cache;

use Closure;

class Cache
{
    /** @var    \Opis\Cache\StorageInterface    Cache storage */
    protected $storage;

    /**
     * Constructor
     *
     * @param   \Opis\Cache\StorageInterface    $storage    Cache storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Read a value from cache
     *
     * @param   string  $key    Cache key
     *
     * @return  mixed
     */
    public function read($key)
    {
        return $this->storage->read($key);
    }

    /**
     * Save in cache
     *
     * @param   string  $key    Cache key
     * @param   mixed   $value  The value that needs to be stored
     * @param   int     $ttl    (optional) Time to life
     *
     * @return  boolean
     */
    public function write($key, $value, $ttl = 0)
    {
        return $this->storage->write($key, $value, $ttl);
    }

    /**
     * Delete from cache
     *
     * @param   string  $key    Cache key
     *
     * @return  boolean
     */
    public function delete($key)
    {
        return $this->storage->delete($key);
    }

    /**
     * Check if cache exists for the specifed key
     *
     * @param   string  $key    Cache key
     *
     * @return  boolean
     */
    public function has($key)
    {
        return $this->storage->has($key);
    }

    /**
     * Clear the cache
     *
     * @return  boolean
     */
    public function clear()
    {
        return $this->storage->clear();
    }

    /**
     * Read from cache. If the specified key doesn't exist or the cache expired, then store in cache
     * the value obtained by invoking the given closure and then return the stored value
     *
     * @param   string      $key        Cache key
     * @param   \Closure    $closure    Callback closure
     * @param   int         $ttl        (optional) Time to life
     *
     * @return  mixed
     */
    public function load($key, Closure $closure, $ttl = 0)
    {
        if ($this->storage->has($key)) {
            return $this->storage->read($key);
        }
        $value = $closure($key);
        $this->storage->write($key, $value, $ttl);
        return $value;
    }
}
