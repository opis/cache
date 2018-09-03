<?php
/* ===========================================================================
 * Copyright 2018 Zindex Software
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

namespace Opis\Cache\Drivers;

use RuntimeException;
use Opis\Cache\{
    CacheInterface, LoadTrait
};

class File implements CacheInterface
{
    use LoadTrait;

    /** @var    string */
    protected $path;

    /** @var    string */
    protected $prefix;

    /** @var    string */
    protected $extension;

    /**
     * Constructor.
     *
     * @param   string $path Path
     * @param   string $prefix (optional) Cache key prefix
     * @param   string $extension (optional) File extension
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
            throw new RuntimeException(vsprintf("Cache directory ('%s') does not exist.", [$this->path]));
        }

        if (!is_writable($this->path) || !is_readable($this->path)) {
            throw new RuntimeException(vsprintf("Cache directory ('%s') is not writable or readable.", [$this->path]));
        }
    }

    /**
     * Read from cache
     *
     * @param string $key
     * @return mixed|false
     */
    public function read(string $key)
    {
        if (file_exists($this->cacheFile($key))) {
            // Cache exists
            $handle = fopen($this->cacheFile($key), 'r');
            $expire = (int)trim(fgets($handle));

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
     * Write to cache
     *
     * @param string $key
     * @param $data
     * @param int $ttl
     * @return bool
     */
    public function write(string $key, $data, int $ttl = 0): bool
    {
        $ttl = ((int)$ttl <= 0) ? 0 : ((int)$ttl + time());
        $file = $this->cacheFile($key);
        $data = "{$ttl}\n" . serialize($data);

        return $this->fileWrite($file, $data);
    }

    /**
     * Delete from cache
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        $file = $this->cacheFile($key);

        if (file_exists($file)) {
            return unlink($file);
        }

        return false;
    }

    /**
     * Check if the cache entry exists
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $file = $this->cacheFile($key);

        if (file_exists($file)) {
            $handle = fopen($file, 'r');
            $expire = (int)trim(fgets($handle));
            fclose($handle);
            return $expire === 0 || time() < $expire;
        }

        return false;
    }

    /**
     * Clear cache
     *
     * @return bool
     */
    public function clear(): bool
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

    /**
     * Returns the path to the cache file.
     *
     * @param   string $key Cache key
     *
     * @return  string
     */
    protected function cacheFile(string $key): string
    {
        return $this->path . '/' . $this->prefix . $key . $this->extension;
    }

    /**
     * Write on file
     *
     * @param   string $file File path
     * @param   string $data Content
     *
     * @return  bool
     */
    protected function fileWrite(string $file, string $data): bool
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

}