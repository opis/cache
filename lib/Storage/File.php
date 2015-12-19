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

class File implements StorageInterface
{
    /** @var    string */
    protected $path;

    /** @var    string */
    protected $prefix;

    /** @var    string */
    protected $extension;

    /**
     * Constructor.
     *
     * @param   string  $path       Path
     * @param   string  $prefix     (optional) Cache key prefix
     * @param   string  $extension  (optional) File extension
     */
    public function __construct($path, $prefix = '', $extension = 'cache')
    {
        $this->path = rtrim($path, '/');
        $this->prefix = trim($prefix, '.');
        $this->extension = trim($extension, '.');

        if ($this->prefix !== '') {
            $this->prefix .= '.';
        }

        if ($this->extension !== '') {
            $this->extension = '.' . $this->extension;
        }

        if (!is_dir($this->path) && !@mkdir($this->path, 0775, true)) {
            throw new RuntimeException(vsprintf("Cache directory ('%s') does not exist.", array($this->path)));
        }

        if (!is_writable($this->path) || !is_readable($this->path)) {
            throw new RuntimeException(vsprintf("Cache directory ('%s') is not writable or readable.", array($this->path)));
        }
    }

    /**
     * Returns the path to the cache file.
     * 
     * @param   string  $key  Cache key
     * 
     * @return  string
     */
    protected function cacheFile($key)
    {
        return $this->path . '/' . $this->prefix . $key . $this->extension;
    }

    /**
     * Write on file
     *
     * @param   string  &$file  File path
     * @param   string  &$data  Content
     * 
     * @return  true
     */
    protected function fileWrite(&$file, &$data)
    {
        $fh = fopen($file, 'c');
        flock($fh, LOCK_EX);
        chmod($file, 0774);
        ftruncate($fh, 0);
        fwrite($fh, $data);
        flock($fh, LOCK_UN);
        fclose($fh);
        return true;
    }

    /**
     * Store variable in the cache.
     *
     * @param   string   $key    Cache key
     * @param   mixed    $value  The variable to store
     * @param   int      $ttl    (optional) Time to live
     * 
     * @return  boolean
     */
    public function write($key, $value, $ttl = 0)
    {
        $ttl = ((int) $ttl <= 0) ? 0 : ((int) $ttl + time());
        $file = $this->cacheFile($key);
        $data = "{$ttl}\n" . serialize($value);

        return $this->fileWrite($file, $data);
    }

    /**
     * Fetch variable from the cache.
     *
     * @param   string  $key  Cache key
     * 
     * @return  mixed
     */
    public function read($key)
    {
        if (file_exists($this->cacheFile($key))) {
            // Cache exists

            $handle = fopen($this->cacheFile($key), 'r');

            $expire = (int) trim(fgets($handle));

            if ($expire === 0 || time() < $expire) {
                $cache = '';
                while (!feof($handle)) {
                    $cache .= fgets($handle);
                }
                fclose($handle);
                return unserialize($cache);
            }

            fclose($handle);
            unlink($this->cacheFile($key));
        }

        return false;
    }

    /**
     * Returns TRUE if the cache key exists and FALSE if not.
     * 
     * @param   string   $key  Cache key
     * 
     * @return  boolean
     */
    public function has($key)
    {
        $file = $this->cacheFile($key);

        if (file_exists($file)) {
            $handle = fopen($file, 'r');

            $expire = (int) trim(fgets($handle));

            fclose($handle);

            return $expire === 0 || time() < $expire;
        }

        return false;
    }

    /**
     * Delete a variable from the cache.
     *
     * @param   string   $key  Cache key
     * 
     * @return  boolean
     */
    public function delete($key)
    {
        $file = $this->cacheFile($key);

        if (file_exists($file)) {
            return unlink($file);
        }

        return false;
    }

    /**
     * Clears the user cache.
     *
     * @return  boolean
     */
    public function clear()
    {
        $pattern = $this->path . '/' . $this->prefix . '*' . $this->extension;

        foreach (glob($pattern) as $file) {
            if (!is_dir($file)) {
                if (unlink($file) === false) {
                    return false;
                }
            }
        }

        return true;
    }
}
