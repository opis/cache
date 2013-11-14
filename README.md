##Opis Cache##
==============

```php
use \Opis\Cache\Cache;
use \Opis\Cache\Storage\Memory;

$cache = new Cache(new Memory());
$cache->write('key', 'value');
```