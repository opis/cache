<?php

namespace Opis\Cache\Storage;

use RuntimeException;
use Opis\Cache\AbstractStorage;

class APC extends AbstractStorage
{
	
	/**
	 * Constructor.
	 *
	 * @access  public
	 * @param   string   $identifier  Identifier
	 */
        
	public function __construct($identifier)
	{
		parent::__construct($identifier);
		
		if(function_exists('apc_fetch') === false)
		{
			throw new RuntimeException(vsprintf("%s(): APC is not available.", array(__METHOD__)));
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
	    return apc_store($this->identifier . $key, $value, $ttl);
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
	    return apc_fetch($this->identifier . $key);
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
	    return apc_exists($this->identifier . $key);
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
	    return apc_delete($this->identifier . $key);
	}
        
	/**
	 * Clears the user cache.
	 *
	 * @access  public
	 * @return  boolean
	 */
        
	public function clear()
	{
	    return apc_clear_cache('user');
	}
}