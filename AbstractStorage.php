<?php

namespace Opis\Cache;


abstract class AbstractStorage implements StorageInterface
{

    /** @var    string  Cache identifier. */
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

}