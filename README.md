Opis Cache
==============
[![Latest Stable Version](https://poser.pugx.org/opis/cache/version.png)](https://packagist.org/packages/opis/cache)
[![Latest Unstable Version](https://poser.pugx.org/opis/cache/v/unstable.png)](//packagist.org/packages/opis/cache)
[![License](https://poser.pugx.org/opis/cache/license.png)](https://packagist.org/packages/opis/cache)

Caching library
----------------
**Opis Cache** library that helps you work with cached content. 
Cached content can be stored and retrieved by using one of the provided cache driver.
You can create your own cache driver by simply implementing an interface.

The currently supported cache drivers are: File, Memory, and PHPFile.

### License

**Opis Cache** is licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0). 

### Requirements

* PHP 7.0.* or higher

### Installation

This library is available on [Packagist](https://packagist.org/packages/opis/cache) and can be installed using [Composer](http://getcomposer.org).

```json
{
    "require": {
        "opis/cache": "4.0.x-dev"
    }
}
```

If you are unable to use [Composer](http://getcomposer.org) you can download the
[tar.gz](https://github.com/opis/cache/archive/master.tar.gz) or the [zip](https://github.com/opis/cache/archive/master.zip)
archive file, extract the content of the archive and include de `autoload.php` file into your project. 

```php

require_once 'path/to/cache-master/autoload.php';

```

### Documentation

Examples and documentation(outdated) can be found [here](http://opis.io/cache).
