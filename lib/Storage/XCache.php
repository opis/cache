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

use RuntimeException;
use Opis\Cache\StorageInterface;

class XCache implements StorageInterface
{
    /** @var    string  XCache username */
    protected $username;

    /** @var    string  XCache password */
    protected $password;

    /** @var    string */
    protected $prefix;

    /**
     * Constructor.
     *
     * @param   string  $username   Username
     * @param   string  $password   Password
     * @param   string  $prefix     (optional) Cache key prefix
     */
    public function __construct($username, $password, $prefix = '')
    {
        $this->username = $username;

        $this->password = $password;

        $this->prefix = $prefix;

        if (function_exists('xcache_get') === false) {
            throw new RuntimeException(vsprintf("%s(): XCache is not available.", array(__METHOD__)));
        }
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
        return xcache_set($this->prefix . $key, serialize($value), $ttl);
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
        return unserialize(xcache_get($this->prefix . $key));
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
        return xcache_isset($this->prefix . $key);
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
        return xcache_unset($this->prefix . $key);
    }

    /**
     * Clears the user cache.
     *
     * @return  boolean
     */
    public function clear()
    {
        $cleared = true;

        $tempUsername = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : false;
        $tempPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : false;

        $_SERVER['PHP_AUTH_USER'] = $this->username;
        $_SERVER['PHP_AUTH_PW'] = $this->password;

        $cacheCount = xcache_count(XC_TYPE_VAR);

        for ($i = 0; $i < $cacheCount; $i++) {
            if (@xcache_clear_cache(XC_TYPE_VAR, $i) === false) {
                $cleared = false;
                break;
            }
        }

        if ($tempUsername !== false) {
            $_SERVER['PHP_AUTH_USER'] = $tempUsername;
        } else {
            unset($_SERVER['PHP_AUTH_USER']);
        }

        if ($tempPassword !== false) {
            $_SERVER['PHP_AUTH_PW'] = $tempPassword;
        } else {
            unset($_SERVER['PHP_AUTH_PW']);
        }

        return $cleared;
    }
}
