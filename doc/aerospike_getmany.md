
# Aerospike::getMany

Aerospike::getMany - gets a batch of record from the Aerospike database

## Description

```
public int Aerospike::getMany ( array $keys, array &$records [, array $filter [, array $options]] )
```

**Aerospike::getMany()** will read a batch of *records* from a list of given
*keys*, and fill *records* with the resulting indexed array. Each record is an array
consisting of *key*, *metadata* and *bins* (see: [get()](aerospike_get.md)).
Non-existent records will have NULL for their *metadata* and *bins* fields.
The bins returned can be filtered by passing an array of bin names.

## Parameters

**keys** an array of initialized keys, each key an array with keys ['ns','set','key'] or ['ns','set','digest'].

**records** filled by an indexed array of [record](aerospike_get.md) values.

**filter** an array of bin names. Non-existent bins have a NULL value.

**[options](aerospike.md)** including
- **Aerospike::OPT_READ_TIMEOUT**

## Return Values

Returns an integer status code.  Compare to the Aerospike class status
constants.  When non-zero the **Aerospike::error()** and
**Aerospike::errorno()** methods can be used.

## Examples

### Example #1 Aerospike::getMany() default behavior example

```php
<?php

$config = array("hosts"=>array(array("addr"=>"localhost", "port"=>3000)));
$db = new Aerospike($config);
if (!$db->isConnected()) {
   echo "Aerospike failed to connect[{$db->errorno()}]: {$db->error()}\n";
   exit(1);
}

$key1 = $db->initKey("test", "users", 1234);
$key2 = $db->initKey("test", "users", 1235); // this key does not exist
$key3 = $db->initKey("test", "users", 1236);
$keys = array($key1, $key2, $key3);
$status = $db->getMany($keys, $records);
if ($status == Aerospike::OK) {
    var_dump($records);
} else {
    echo "[{$db->errorno()}] ".$db->error();
}

?>
```

We expect to see:

```
array(3) {
  [0]=>
  array(3) {
    ["key"]=>
    array(4) {
      ["ns"]=>
      string(4) "test"
      ["set"]=>
      string(5) "users"
      ["key"]=>
      int(1234)
      ["digest"]=>
      string(20) "M?v2Kp???

?[??4?v
    }
    ["metadata"]=>
    array(2) {
      ["ttl"]=>
      int(4294967295)
      ["generation"]=>
      int(1)
    }
    ["bins"]=>
    array(3) {
      ["email"]=>
      string(15) "hey@example.com"
      ["name"]=>
      string(9) "You There"
      ["age"]=>
      int(33)
    }
  }
  [1]=>
  array(3) {
    ["key"]=>
    array(4) {
      ["ns"]=>
      string(4) "test"
      ["set"]=>
      string(5) "users"
      ["key"]=>
      int(1235)
      ["digest"]=>
      string(20) "?C??[?vwS??ƨ?????"
    }
    ["metadata"]=>
    NULL
    ["bins"]=>
    NULL
  }
  [2]=>
  array(3) {
    ["key"]=>
    array(4) {
      ["ns"]=>
      string(4) "test"
      ["set"]=>
      string(5) "users"
      ["key"]=>
      int(1236)
      ["digest"]=>
      string(20) "'?9?
                      ??????
?	?"
    }
    ["metadata"]=>
    array(2) {
      ["ttl"]=>
      int(4294967295)
      ["generation"]=>
      int(1)
    }
    ["bins"]=>
    array(3) {
      ["email"]=>
      string(19) "thisguy@example.com"
      ["name"]=>
      string(8) "This Guy"
      ["age"]=>
      int(42)
    }
  }
}
```

### Example #2 getMany the record with filtered bins

```php
<?php

// assuming this follows Example #1

// Getting a filtered record
$filter = array("email");
$keys = array($key1, $key3);
$status = $db->getMany($keys, $records, $filter);
if ($status == Aerospike::OK) {
    var_dump($records);
} else {
    echo "[{$db->errorno()}] ".$db->error();
}

?>
```

We expect to see:

```
array(2) {
  [0]=>
  array(3) {
    ["key"]=>
    array(4) {
      ["ns"]=>
      string(4) "test"
      ["set"]=>
      string(5) "users"
      ["key"]=>
      int(1234)
      ["digest"]=>
      string(20) "M?v2Kp???

?[??4?v
    }
    ["metadata"]=>
    array(2) {
      ["ttl"]=>
      int(4294967295)
      ["generation"]=>
      int(4)
    }
    ["bins"]=>
    array(1) {
      ["email"]=>
      string(15) "hey@example.com"
    }
  }
  [1]=>
  array(3) {
    ["key"]=>
    array(4) {
      ["ns"]=>
      string(4) "test"
      ["set"]=>
      string(5) "users"
      ["key"]=>
      int(1236)
      ["digest"]=>
      string(20) "'?9?
                      ??????
?	?"
    }
    ["metadata"]=>
    array(2) {
      ["ttl"]=>
      int(4294967295)
      ["generation"]=>
      int(4)
    }
    ["bins"]=>
    array(1) {
      ["email"]=>
      string(19) "thisguy@example.com"
    }
  }
}
```
