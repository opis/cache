<?php

namespace Opis\Cache;

class Cache {
    
    protected $storage;
    
    public function __construct(AbstractStorage $storage)
    {
        $this->storage = $storage;
    }
    
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->storage, $name), $arguments);
    }
    
}
