<?php

namespace Opis\Cache;

use Closure;

abstract class AbstractStorage {

    /** @var string Cache identifier. */
    protected $identifier;

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

    abstract public function write($key, $value, $ttl = 0);

    abstract public function read($key);

    abstract public function has($key);

    abstract public function delete($key);

    abstract public function clear();
    
    final public function load($key, Closure $callback, $ttl = 0)
    {
        if($this->has($key))
        {
            return $this->read($key);
        }
        $value = $callback();
        $this->write($key, $value, $ttl);
        return $value;
    }
    
    /**
     * Magic setter.
     *
     * @access  public
     * @param   string  $key    Cache key
     * @param   mixed   $value  The variable to store
     */

    final public function __set($key, $value)
    {
        $this->write($key, $value);
    }

    /**
     * Magic getter.
     *
     * @access  public
     * @param   string  $key  Cache key
     * @return  mixed
     */

    final public function __get($key)
    {
        return $this->read($key);
    }

    /**
     * Magic isset.
     *
     * @access  public
     * @param   string   $key  Cache key
     * @return  boolean
     */

    final public function __isset($key)
    {
        return ($this->read($key) !== false);
    }

    /**
     * Magic unsetter.
     *
     * @access  public
     * @param   string  $key  Cache key
     */

    final public function __unset($key)
    {
        $this->delete($key);
    }
}