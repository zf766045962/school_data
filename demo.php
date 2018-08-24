<?php
/**
 * demo.php
 * @author: tangzhihui
 * @date: 17/7/20 18:26
 */

require_once dirname(dirname(__DIR__)) . '/index.php';

$ret = DB::query(Database::SELECT,'select * from intent;')->execute();
var_dump($ret->as_array());