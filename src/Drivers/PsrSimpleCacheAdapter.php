<?php
/* ============================================================================
 * Copyright © 2013-2018 The Opis Project
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
use Psr\SimpleCache\CacheInterface as PsrCacheInterface;

class PsrSimpleCacheAdapter implements CacheInterface
{
    use LoadTrait;

    /** @var PsrCacheInterface */
    protected $cache;

    /**
     * @param PsrCacheInterface $cache
     */
    public function __construct(PsrCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return PsrCacheInterface
     */
    public function psrInstance(): PsrCacheInterface
    {
        return $this->cache;
    }

    /**
     * @inheritDoc
     */
    public function read(string $key)
    {
        return $this->cache->get($key, false);
    }

    /**
     * @inheritDoc
     */
    public function write(string $key, $data, int $ttl = 0): bool
    {
        return $this->cache->set($key, $data, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }

    /**
     * @inheritDoc
     */
    public function clear(): bool
    {
        return $this->cache->clear();
    }
}