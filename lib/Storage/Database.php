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
use Opis\Cache\StorageInterface;
use Opis\Database\Connection;
use Opis\Database\Database as OpisDatabase;


class Database implements StorageInterface
{
    /** @var    \Opis\Database\Database Database. */
    protected $db;
    
    /** @var    string  Cache table. */
    protected $table;
    
    /** @var    string  Table prefix. */
    protected $prefix;
    
    /** @var    array   Column map. */
    protected $columns;
     
    public function __construct(Connection $connection, $table, $prefix = '', $columns = null)
    {
        $this->db = new OpisDatabase($connection);
        $this->table = $table;
        $prefix = trim($prefix);
        $this->prefix = $prefix === '' ? '' : $prefix . '.';
        
        if($columns === null || !is_array($columns))
        {
            $columns = array();
        }
        
        $columns += array(
            'key' => 'key',
            'data' => 'data',
            'ttl' => 'ttl',
        );
        
        $this->columns = $columns;
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
            
            return $this->db->into($this->table)->insert(array(
                $this->columns['key'] => $this->prefix . $key,
                $this->columns['data'] => serialize($value),
                $this->columns['ttl'] => $ttl,
            ));
            
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
            $cache = $this->db->form($this->table)
                              ->where($this->columns['key'], $this->prefix . $key)
                              ->select()
                              ->first();
                        
            if($cache !== false)
            {
                if(time() < $cache->{$this->columns['ttl']})
                {
                    return unserialize($cache->{$this->columns['data']});
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
            return (bool) $this->db->from($this->table)
                                   ->where($this->columns['key'], $this->prefix . $key)
                                   ->andWhere($this->columns['ttl'], time(), '>')
                                   ->count();
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
            return (bool) $this->db->from($this->table)
                                   ->where($this->columns['key'], $this->prefix . $key)
                                   ->delete();
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
            $this->db->from($this->table)->delete();
            
            return true;
        }
        catch(PDOException $e)
        {
            return false;
        }
    }
    
}
