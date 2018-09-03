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

use Opis\Cache\{
    CacheInterface, LoadTrait
};

class Memory implements CacheInterface
{
    use LoadTrait;

    /** @var array */
    protected $cache = [];

    /**
     * Read from cache
     *
     * @param string $key
     * @return mixed|false
     */
    public function read(string $key)
    {
        if (isset($this->cache[$key])) {
            $expire = (int)$this->cache[$key]['ttl'];

            if ($expire === 0 || time() < $expire) {
                return $this->cache[$key]['data'];
            }

            $this->delete($key);
            return false;
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
        $this->cache[$key] = ['data' => $data, 'ttl' => $ttl];

        return true;
    }

    /**
     * Delete from cache
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
            return true;
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
        if (isset($this->cache[$key])) {
            $expire = (int)$this->cache[$key]['ttl'];
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
        $this->cache = [];

        return true;
    }
}