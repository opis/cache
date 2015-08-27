<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2015 Marius Sarca
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

use RuntimeException;
use Opis\Cache\StorageInterface;

class PHPFile implements StorageInterface
{

    protected $path;
    
    protected $prefix;
    
    protected $extension;
    
    /**
     * Constructor.
     *
     * @access  public
     * 
     * @param	string	$path       Path
     * @param	string	$prefix     Prefix
     * @param   string  $extension  File extension
     */
    
    public function __construct($path, $prefix = '', $extension = 'php')
    {
        $this->path = rtrim($path, '/');
        $prefix = trim($prefix, '.');
        $extension = trim($extension, '.');
        
        $this->prefix = $prefix === '' ? '' : $prefix . '.';
        $this->extension = $extension === '' ? '' : '.' . $extension;
        
        if(file_exists($this->path) === false || is_readable($this->path) === false || is_writable($this->path) === false)
        {
            throw new RuntimeException(vsprintf("%s(): Cache directory ('%s') is not writable.", array(__METHOD__, $this->path)));
        }
    }
    
    
    /**
     * Returns the path to the cache file.
     * 
     * @access  public
     * @param   string  $key  Cache key
     * @return  string
     */
    
    protected function cacheFile($key)
    {
        return $this->path . '/' . $this->prefix . $key . $this->extension;
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
        $ttl = ((int) $ttl <= 0) ? 0 : ((int) $ttl + time());
        
        $data = "<?php\n\rreturn " . preg_replace('/\s=>\s(\n\s+)array\s\(\n/', " => array (\n", var_export(array('ttl'=> $ttl, 'data' => serialize($value)), true)) . ';';
        
        return is_int(file_put_contents($this->cacheFile($key), $data, LOCK_EX));
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
        $file = $this->cacheFile($key);
        
        if(file_exists($file))
        {
            // Cache exists
            
            $data = include $file;
            
            $expire = (int) $data['ttl'];
            
            if($expire === 0 || time() < $expire)
            {
                return unserialize($data['data']);
            }
                
            unlink($file);
        }
        
        return false;
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
        $file = $this->cacheFile($key);
        
        if(file_exists($file))
        {
            $data = include $file;
            $expire = (int) $data['ttl'];
            
            return $expire === 0 || time() < $expire;
        }
        
        return false;
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
        $file = $this->cacheFile($file);
        
        if(file_exists($file))
        {
            return unlink($file);
        }
        
        return false;
    }
    
    /**
     * Clears the user cache.
     *
     * @access  public
     * @return  boolean
     */
    
    public function clear()
    {
        $pattern = $this->path . '/' . $this->prefix . '*' . $this->extension;
        
        foreach(glob($pattern) as $file)
        {
            if(!is_dir($file))
            {
                if(unlink($file) === false)
                {
                    return false;
                }
            }
        }
        
        return true;
    }
}
