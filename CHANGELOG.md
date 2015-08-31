CHANGELOG
-----------
### Opis Cache 3.0.0, 2015.08.31

* Removed `Opis\Cache\StorageCollection` class
* `Opis\Cache\Storage\Database` and `Opis\Cache\Storage\Redis` classes were moved into the
`opis/storages` package
* Removed `opis\database`, `opis\closure` and `predis\predis` dependencies.

### Opis Cache 2.3.1, 2015.08.27

* Modified `write`, `read` and `has` methods in `Database` storage
* Modified `write`, `read` and `has` methods in `File` storage
* Modified `write`, `read` and `has` methods in `Memory` storage
* Modified `write`, `read` and `has` methods in `Mongo` storage
* Modified `write`, `read` and `has` methods in `PHPFile` storage
* Modified `write`, `read` and `has` methods in `Proxy` storage

### Opis Cache 2.3.0, 2015.07.31

* Updated `opis/database` library dependency to version `^2.1.1`
* Updated `opis/closure` library dependency to version `^2.0.0`
* Removed `branch-alias` property from `composer.json`

### Opis Cache 2.2.0, 2015.07.31

* Updated `opis/closure` library dependency to version `~2.0.*`

### Opis Cache 2.1.0, 2014.11.22

* Updated `predis/predis` library dependency to version `1.0.*`

### Opis Cache 2.0.0, 2014.11.15

* `Opis\Cache\Cache` class no longer implement `Opis\Cache\StorageInterface` interface
* Removed `__call` macgic method from `Opis\Cache\Cache` class
* Commented code
* Added autoload file

### Opis Cache 1.7.0, 2014.10.23

* Updated `opis/database` library dependency to version `2.0.*`
* Updated `opis/closure` library dependency to version `1.3.*`

### Opis Cache 1.6.0, 2014.06.26

* Updated `opis/database` library dependency to version `1.3.*`

### Opis Cache 1.5.2

* Started changelog
