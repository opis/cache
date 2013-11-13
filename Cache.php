<?php

namespace Opis\Cache;

use Closure;

class Cache
{
    
    protected $storage;
    
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
    
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->storage, $name), $arguments);
    }
    
    final public function load($key, Closure $closure, $ttl = 0)
    {
        if($this->storage->has($key))
        {
            return $this->storage->read($key);
        }
        $value = $closure();
        $this->storage->write($key, $value, $ttl);
        return $value;
    }
    
}
