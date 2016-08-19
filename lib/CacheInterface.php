<?php
/* ===========================================================================
 * Copyright 2013-2016 The Opis Project
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

namespace Opis\Cache;

interface CacheInterface
{
    /**
     * Read from cache
     *
     * @param string $key
     * @return mixed|false
     */
    public function read(string $key);

    /**
     * Write to cache
     *
     * @param string $key
     * @param $data
     * @param int $ttl
     * @return bool
     */
    public function write(string $key, $data, int $ttl = 0): bool;

    /**
     * Load an item from cache
     *
     * @param string $key
     * @param callable $loader
     * @param int $ttl
     * @return mixed
     */
    public function load(string $key, callable $loader, int $ttl = 0);

    /**
     * Delete from cache
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * Check if the cache entry exists
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Clear cache
     *
     * @return bool
     */
    public function clear(): bool;
}