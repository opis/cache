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

class CacheStorage
{    
    protected static $storages = array();
    
    protected static $defaultStorage = null;
    
    protected static $instances = array();

    protected function __construct()
    {
        
    }
    
    public static function register($name, Closure $closure, $default = false)
    {
        static::$storages[$name] = $closure;
        
        if($default || empty(static::$storages))
        {
            self::$defaultStorage = $name;
        }
    }
    
    public static function defaultStorageName()
    {
        return static::$defaultStorage;
    }
    
    public static function build($name = null)
    {
        if($name == null)
        {
            $name = static::$defaultStorage;
        }
        
        if(!isset(static::$instances[$name]))
        {
            static::$instances[$name] = static::$storages[$name]();
        }
        
        return static::$instances[$name];
    }

}