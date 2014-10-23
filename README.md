Opis Cache
==============
[![Latest Stable Version](https://poser.pugx.org/opis/cache/version.png)](https://packagist.org/packages/opis/cache)
[![Latest Unstable Version](https://poser.pugx.org/opis/cache/v/unstable.png)](//packagist.org/packages/opis/cache)
[![License](https://poser.pugx.org/opis/cache/license.png)](https://packagist.org/packages/opis/cache)

Cache library with support for multiple storages.

Supported storages:

* APC
* APCU
* Database (MySQL, PostgreSQL, MS SQL Server, Oracle, DB2, SQLite, Firebird, NuoDB)
* File
* Memcache
* Memcached
* Memory
* MongoDB
* Proxy
* Redis
* WinCache
* XCache
* ZendDisk
* ZendMemory

###Installation

This library is available on [Packagist](https://packagist.org/packages/opis/cache) and can be installed using [Composer](http://getcomposer.org)

```json
{
    "require": {
        "opis/cache": "1.7.*"
    }
}
```
###Documentation

###Examples

```php
use \Opis\Cache\Cache;
use \Opis\Cache\Storage\File as FileStorage;

$cache = new Cache(new FileStorage('/path/to/folder'));
$cache->write('key', 'value');

if($cache->has('key'))
{
    $value = $cache->read('key');
}

$cache->delete('key');

$value = $cache->load('key', function(){
    return array();
}, 3600);

```

Using a `StorageCollection`

```php
use \Opis\Cache\Storage\Memory as MemoryStorage;
use \Opis\Cache\StorageCollection;

$collection = new StorageCollection();

$collection->add('memory', function(){
    return new MemoryStorage();
});

$collection->get('memory')->write('name', 'Opis Cache');

//Serialize - unserialize

$collection = unserialize(serialize($collection));


print collection->get('memory')->read('name'); //> Opis Cache

```
