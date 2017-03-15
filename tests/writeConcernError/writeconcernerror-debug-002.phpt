--TEST--
MongoDB\Driver\WriteConcernError debug output
--SKIPIF--
<?php require __DIR__ . "/../utils/basic-skipif.inc"; ?>
<?php SLOW(); NEEDS('REPLICASET_30'); CLEANUP(REPLICASET_30); ?>
--FILE--
<?php
require_once __DIR__ . "/../utils/basic.inc";

$manager = new MongoDB\Driver\Manager(REPLICASET_30);

$bulk = new MongoDB\Driver\BulkWrite;
for ($i = 0; $i < 6; $i++) {
    $bulk->insert(['x' => $i, 'y' => str_repeat('a', 4194304)]);
}

try {
    $manager->executeBulkWrite(NS, $bulk, new MongoDB\Driver\WriteConcern(2, 1));
} catch(MongoDB\Driver\Exception\BulkWriteException $e) {
    var_dump($e->getWriteResult()->getWriteConcernError());
}

?>
===DONE===
<?php exit(0); ?>
--EXPECTF--
object(MongoDB\Driver\WriteConcernError)#%d (%d) {
  ["message"]=>
  string(33) "waiting for replication timed out"
  ["code"]=>
  int(64)
  ["info"]=>
  object(stdClass)#%d (%d) {
    ["wtimeout"]=>
    bool(true)
  }
}
===DONE===
