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

use Opis\Cache\StorageInterface;

class Memory implements StorageInterface
{
    /** @var    array   Cached data. */
    protected $cache = array();

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
        $ttl = ((int) $ttl <= 0) ? 0 : ((int) $ttl + time());

        $this->cache[$key] = array('data' => $value, 'ttl' => $ttl);

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
        if (isset($this->cache[$key])) {
            $expire = (int) $this->cache[$key]['ttl'];

            if ($expire === 0 || time() < $expire) {
                return $this->cache[$key]['data'];
            }

            $this->delete($key);
            return false;
        }

        return false;
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
        if (isset($this->cache[$key])) {
            $expire = (int) $this->cache[$key]['ttl'];
            return $expire === 0 || time() < $expire;
        }

        return false;
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
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
            return true;
        }

        return false;
    }

    /**
     * Clears the user cache.
     *
     * @return  boolean
     */
    public function clear()
    {
        $this->cache = array();

        return true;
    }
}
