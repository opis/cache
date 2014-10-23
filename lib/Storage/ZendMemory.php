<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2014 Marius Sarca
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
use Opis\Cache\StorageInterface;

class ZendMemory implements StorageInterface
{
    protected $prefix;
    
    /**
     * Constructor.
     *
     * @access  public
     * @param   string  $identifier Cache identifier
     */
    
    public function __construct($prefix = '')
    {
        $this->prefix = $prefix;
        
        if(function_exists('zend_shm_cache_fetch') === false)
        {
            throw new RuntimeException(vsprintf("%s(): Zend Data Cache is not available.", array(__METHOD__)));
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
        return zend_shm_cache_store($this->prefix . $key, $value, $ttl);
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
        return zend_shm_cache_fetch($this->prefix . $key);
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
        return (zend_disk_cache_fetch($this->prefix . $key) !== false);
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
        return zend_shm_cache_delete($this->prefix . $key);
    }
    
    /**
     * Clears the user cache.
     *
     * @access  public
     * @return  boolean
     */
    
    public function clear()
    {
        return zend_shm_cache_clear();
    }
}
