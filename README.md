Opis Cache
==============
[![Tests](https://github.com/opis/cache/workflows/Tests/badge.svg)](https://github.com/opis/cache/actions)
[![Latest Stable Version](https://poser.pugx.org/opis/cache/v/stable.png)](https://packagist.org/packages/opis/cache)
[![Latest Unstable Version](https://poser.pugx.org/opis/cache/v/unstable.png)](https://packagist.org/packages/opis/cache)
[![License](https://poser.pugx.org/opis/cache/license.png)](https://packagist.org/packages/opis/cache)

Caching library
----------------
**Opis Cache** is library that helps you work with cached content. 
Cached content can be stored and retrieved by using one of the provided cache driver.
You can create your own cache driver by simply implementing an interface.

The currently supported cache drivers are: File, Memory, and PHPFile.

## Documentation

The full documentation for this library can be found [here][documentation].

## License

**Opis Cache** is licensed under the [Apache License, Version 2.0][license].

## Requirements

* PHP ^7.0

## Installation

**Opis Cache** is available on [Packagist] and it can be installed from a 
command line interface by using [Composer]. 

```bash
composer require opis/cache
```

Or you could directly reference it into your `composer.json` file as a dependency

```json
{
    "require": {
        "opis/cache": "^2020"
    }
}
```

[documentation]: https://opis.io/cache
[license]: https://www.apache.org/licenses/LICENSE-2.0 "Apache License"
[Packagist]: https://packagist.org/packages/opis/cache "Packagist"
[Composer]: https://getcomposer.org "Composer"