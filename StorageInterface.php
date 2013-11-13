<?php

namespace Opis\Cache;

interface StorageInterface
{
  
  function read($key);
  
  function write($key, $value, $ttl = 0);
  
  function delete($key);
  
  function has($key);
  
  function clear();
  
}