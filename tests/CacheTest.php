<?php
/* ============================================================================
 * Copyright 2018-2020 Zindex Software
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

namespace Opis\Cache\Test;

use Opis\Cache\CacheDriver;
use Opis\Cache\Drivers\Memory;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    private CacheDriver $cache;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->cache = new Memory();
    }

    public function testReadWrite()
    {
        $this->assertFalse($this->cache->has('test-key'));
        $this->assertTrue($this->cache->write('test-key', 'test-data'));
        $this->assertTrue($this->cache->has('test-key'));
        $this->assertEquals('test-data', $this->cache->read('test-key'));
    }

    public function testLoad()
    {
        $this->assertFalse($this->cache->has('test-key'));
        $this->assertEquals('test-data', $this->cache->load('test-key', function () {
            return 'test-data';
        }));
        $this->assertTrue($this->cache->has('test-key'));
        $this->assertEquals('test-data', $this->cache->load('test-key', function () {
            return 'OTHER-VALUE';
        }));
    }

    public function testDelete()
    {
        $this->cache->write('test-key', 'test-data');
        $this->assertTrue($this->cache->has('test-key'));
        $this->assertTrue($this->cache->delete('test-key'));
        $this->assertFalse($this->cache->has('test-key'));
    }

    public function testClear()
    {
        $this->cache->write('test-key', 'test-data');
        $this->assertTrue($this->cache->has('test-key'));
        $this->cache->write('test-key2', 'test-data2');
        $this->assertTrue($this->cache->has('test-key2'));

        $this->assertTrue($this->cache->clear());
        $this->assertFalse($this->cache->has('test-key'));
        $this->assertFalse($this->cache->has('test-key2'));
    }

    public function testExpiration()
    {
        $this->cache->write('test-key', 'test-data', 1);
        $this->assertTrue($this->cache->has('test-key'));
        $this->assertEquals('test-data', $this->cache->read('test-key'));
        sleep(1);
        $this->assertFalse($this->cache->has('test-key'));
        $this->assertFalse($this->cache->read('test-key'));
    }
}