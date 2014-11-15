<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2014 Marius Sarca
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
use RuntimeException;
use Serializable;
use Opis\Closure\SerializableClosure;

class StorageCollection implements Serializable
{
    /** @var    array   A list of storages */
    protected $storages = array();
    
    /** @var    array   A list of Opis\Cache\Cache instances */
    protected $instances = array();
    
    /** @var    string  The default storage name */
    protected $defaultStorage;
    
    /**
     * Add a new storage
     *
     * @access  public
     * 
     * @param   string      $storage        Storage name
     * @param   \Closure    $constructor    Storage constructor
     * @param   boolean     $default        (optional) Is default storage
     *
     * @return  \Opis\Cache\StorageCollection   Self reference
     */
    
    public function add($storage, Closure $constructor, $default = false)
    {
        if($this->defaultStorage === null)
        {
            $default = true;
        }
        
        if($default)
        {
            $this->defaultStorage = $storage;
        }
        
        $this->storages[$storage] = $constructor;
        unset($this->instances[$storage]);
        
        return $this;
    }
    
    /**
     * Remove a storage
     *
     * @access  public
     * 
     * @param   string  $storage    Storage name
     *
     * @return  \Opis\Cache\StorageCollection   Self reference
     */
    
    public function remove($storage)
    {
        unset($this->storages[$storage]);
        unset($this->instances[$storage]);
        
        if($this->defaultStorage === $storage)
        {
            $this->defaultStorage = null;
            if(!empty($this->storages))
            {
                $this->defaultStorage = array_shift(array_keys($this->storages));
            }
        }
        
        return $this;
    }
    
    /**
     * Check if the specified storage exists
     *
     * @access  public
     * 
     * @param   string  $storage    Storage name
     *
     * @return  boolean
     */
    
    public function has($storage)
    {
        return isset($this->storages[$storage]);
    }
    
    /**
     * Set the default storage
     *
     * @access  public
     * 
     * @param   string  $storage    Storage name
     *
     * @return  \Opis\Cache\StorageCollection   Self reference
     */
    
    public function setDefault($storage)
    {
        if($this->has($storage))
        {
            $this->defaultStorage = $storage;
        }
        
        return $this;
    }
    
    /**
     * Get an instance of the specifed cache storage
     *
     * @access  public
     * 
     * @param   string  $storage    (option) Storage name
     *
     * @return  \Opis\Cache\Cache
     */
    
    public function get($storage = null)
    {
        if($storage === null)
        {
            $storage = $this->defaultStorage;
        }
        
        if(!$this->has($storage))
        {
            throw new RuntimeException('Unknown storage ' . $storage);
        }
        
        if(!isset($this->instances[$storage]))
        {
            $constructor = $this->storages[$storage];
            $this->instances[$storage] = new Cache($constructor());
        }
        
        return $this->instances[$storage];
    }
    
    /**
     * Perform the serialization
     *
     * @access  public
     * 
     * @return  mixed
     */
    
    public function serialize()
    {
        SerializableClosure::enterContext();
        $object = serialize(array(
            'defaultStorage' => $this->defaultStorage,
            'storages' => array_map(function($value){
               return SerializableClosure::from($value);
            }, $this->storages),
        ));
        SerializableClosure::exitContext();
        return $object;
    }
    
    /**
     * Perform deserialization
     *
     * @access  public
     */
    
    public function unserialize($data)
    {
        $object = SerializableClosure::unserializeData($data);
        $this->defaultStorage = $object['defaultStorage'];
        $this->storages = array_map(function($value){
            return $value->getClosure();
        }, $object['storages']);
    }
    
}
