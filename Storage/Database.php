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

namespace Opis\Cache\Storage;

use PDOException;
use Opis\Cache\CacheStorage;
use Opis\Database\Database as DB;


class Database extends CacheStorage
{
    
    protected $connection;
    
    protected $table;
    
    public function __construct($identifier, $connection, $table)
    {
        parent::__construct($identifier);
        $this->connection = $connection;
        $this->table = $table;
    }
    
    
    protected function table()
    {
        return DB::connection($this->connection)->table($this->table);
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
		$ttl = (((int) $ttl === 0) ? 31556926 : (int) $ttl) + time();
		
		try
		{
			$this->delete($key);
			return $this->table()->insert(array('key' => $this->identifier . $key, 'data' => serialize($value), 'lifetime' => $ttl));
		}
		catch(PDOException $e)
		{
			return false;
		}
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
		try
		{
			$cache = $this->table()->where('key', '=', $this->identifier . $key)->first();

			if($cache !== false)
			{
				if(time() < $cache->lifetime)
				{
					return unserialize($cache->data);
				}
				else
				{
					$this->delete($key);
					
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		catch(PDOException $e)
		{
			 return false;
		}
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
		try
		{
			return (bool) $this->table()->where('key', '=', $this->identifier . $key)->where('lifetime', '>', time())->count();
		}
		catch(PDOException $e)
		{
			 return false;
		}
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
		try
		{
			return (bool) $this->table()->where('key', '=', $this->identifier . $key)->delete();
		}
		catch(PDOException $e)
		{
			return false;
		}
	}
	
	/**
	 * Clears the user cache.
	 *
	 * @access  public
	 * @return  boolean
	 */
	
	public function clear()
	{
		try
		{
			$this->table()->delete();
            
			return true;
		}
		catch(PDOException $e)
		{
			return false;
		}
	}
    
}