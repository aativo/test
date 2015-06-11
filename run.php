<?php
require_once('Parse.php');

if(count($argv) != 3){
  echo "Usage: run.php <file> <parameter>" . "\n";
  exit;
} else {
  $file = $argv[1];
  $param = $argv[2];
}

$value = Parse::config($file,$param);
var_dump($value);

?>
