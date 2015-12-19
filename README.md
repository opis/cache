Opis Cache
==============
[![Latest Stable Version](https://poser.pugx.org/opis/cache/version.png)](https://packagist.org/packages/opis/cache)
[![Latest Unstable Version](https://poser.pugx.org/opis/cache/v/unstable.png)](//packagist.org/packages/opis/cache)
[![License](https://poser.pugx.org/opis/cache/license.png)](https://packagist.org/packages/opis/cache)

Caching library
----------------
**Opis Cache** is a caching library, with support for multiple backend storages, that provides developers an API which allows
them to deal with cached content in a standardised way, no matter where that content is stored. Also, the **Opis Cache**'s
simple and effective architecture, ensures that support for new backend storages can be easily achieved
by simply implementing an interface.

The currently supported storages are: APC, APCU, File, Memory, Memcache, Memcached, MongoDB, Proxy, WinCache, XCache, ZendCache and ZendMemory.

##### Important!

You can install additional storage adapters, for SQL databases and Redis, by using the optional [Opis Storages](https://github.com/opis/storages) package.

### License

**Opis Cache** is licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0). 

### Requirements

* PHP 5.3.* or higher

### Installation

This library is available on [Packagist](https://packagist.org/packages/opis/cache) and can be installed using [Composer](http://getcomposer.org).

```json
{
    "require": {
        "opis/cache": "^3.1.1"
    }
}
```

If you are unable to use [Composer](http://getcomposer.org) you can download the
[tar.gz](https://github.com/opis/cache/archive/3.1.1.tar.gz) or the [zip](https://github.com/opis/cache/archive/3.1.1.zip)
archive file, extract the content of the archive and include de `autoload.php` file into your project. 

```php

require_once 'path/to/cache-3.1.1/autoload.php';

```

### Documentation

Examples and documentation can be found at http://opis.io/cache .
