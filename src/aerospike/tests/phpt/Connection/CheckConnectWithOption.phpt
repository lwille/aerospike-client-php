--TEST--
Connection - Check Connect with options.

--SKIPIF--
<?php
include dirname(__FILE__)."/../../astestframework/astest-phpt-loader.inc";
if (!check_for_socket(AEROSPIKE_CONFIG_NAME, AEROSPIKE_CONFIG_PORT)) {
    die("skip tests, the Aerospike DB is not answering at ". AEROSPIKE_CONFIG_NAME. ':'. AEROSPIKE_CONFIG_PORT);
}
--FILE--
<?php
include dirname(__FILE__)."/../../astestframework/astest-phpt-loader.inc";
aerospike_phpt_runtest("Connection", "testConnectWithOption");
--EXPECT--
OK