##Opis Cache Component##

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