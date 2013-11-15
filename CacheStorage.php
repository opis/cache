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

namespace Opis\Cache;

use Closure;

abstract class CacheStorage implements CacheStorageInterface
{

    /** @var    string  Cache identifier. */
    protected $identifier;
    
    protected static $storages = array();
    
    protected static $defaultStorage = null;
    
    protected static $instances = array();

    /**
     * Constructor.
     * 
     * @access  public
     * @param   string  $identifier Cache identifier
     */
    
    public function __construct($identifier)
    {
        $this->identifier = md5($identifier);
    }
    
    public static function register($name, Closure $closure, $default = false)
    {
        self::$storages[$name] = $closure;
        if($default)
        {
            self::$defaultStorage = $name;
        }
    }
    
    public static function getDefaultStorage()
    {
        if(self::$defaultStorage == null)
        {
            if(!empty(self::$storages))
            {
                self::$defaultStorage = reset(array_keys(self::$storages));
            }
        }
        return self::$defaultStorage;
    }
    
    public static function build($name = null)
    {
        if($name == null)
        {
            $name = self::getDefaultStorage();
        }
        if(!isset(self::$instances[$name]))
        {
            self::$instances[$name] = self::$storages[$name]();
        }
        return self::$instances[$name];
    }

    abstract public function write($key, $value, $ttl = 0);

    abstract public function read($key);

    abstract public function has($key);

    abstract public function delete($key);

    abstract public function clear();

}