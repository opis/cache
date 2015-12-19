CHANGELOG
-----------
### v3.1.1, 2015.12.19

* Fixed CS
* Fixed some minor bugs

### v3.1.0, 2015.10.15

* Changed the way `File` and `PHPFile` storages behaves when the specified directory doesn't exits and now
they try to create it first, before throwing an exception.
* Added `Opis\Cache\Storage\File::fileWrite` protected method
* Added `Opis\Cache\Storage\PHPFile::fileWrite` protected method
* Other minor improvements

### v3.0.0, 2015.08.31

* Removed `Opis\Cache\StorageCollection` class
* `Opis\Cache\Storage\Database` and `Opis\Cache\Storage\Redis` classes were moved into the
`opis/storages` package
* Removed `opis\database`, `opis\closure` and `predis\predis` dependencies.

### v2.3.1, 2015.08.27

* Modified `write`, `read` and `has` methods in `Database` storage
* Modified `write`, `read` and `has` methods in `File` storage
* Modified `write`, `read` and `has` methods in `Memory` storage
* Modified `write`, `read` and `has` methods in `Mongo` storage
* Modified `write`, `read` and `has` methods in `PHPFile` storage
* Modified `write`, `read` and `has` methods in `Proxy` storage

### v2.3.0, 2015.07.31

* Updated `opis/database` library dependency to version `^2.1.1`
* Updated `opis/closure` library dependency to version `^2.0.0`
* Removed `branch-alias` property from `composer.json`

### v2.2.0, 2015.07.31

* Updated `opis/closure` library dependency to version `~2.0.*`

### v2.1.0, 2014.11.22

* Updated `predis/predis` library dependency to version `1.0.*`

### v2.0.0, 2014.11.15

* `Opis\Cache\Cache` class no longer implement `Opis\Cache\StorageInterface` interface
* Removed `__call` macgic method from `Opis\Cache\Cache` class
* Commented code
* Added autoload file

### v1.7.0, 2014.10.23

* Updated `opis/database` library dependency to version `2.0.*`
* Updated `opis/closure` library dependency to version `1.3.*`

### v1.6.0, 2014.06.26

* Updated `opis/database` library dependency to version `1.3.*`

### v1.5.2

* Started changelog
