##Opis Cache##

```php
use \Opis\Cache\Cache;
use \Opis\Cache\CacheStorage;
use \Opis\Cache\Storage\Memory as MemoryStorage;
use \Opis\Cache\Storage\File as FileStorage;

CacheStorage::register('memory', function(){
    return new MemoryStorage();
});

//register a storage and mark it as the default cache storage
CacheStorage::register('file', function(){
    return new FileStorage('opis', '/path/to/folder');
}, true);

$cache = Cache::get('memory');
$cache->write('key', 'value');

//use the defult cache storage
Cache::get()->write('key', 'value');
```