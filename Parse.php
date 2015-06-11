<?php

class Parse {
  static $config;
  static $lineBreak = "\n";
  static $translate = array(
	'on' => true, 
	'off' => false, 
	'yes' => true, 
	'no' => false, 
	'true' => true, 
	'false' => false);   

  function __construct(){
  }

  static function config($file = null,$param = null){
    try {
      $content = @file_get_contents($file);
      if($content === false){
        throw new Exception ("Failed opening file: " . $file);
      } else {
        self::$config = explode(self::$lineBreak,$content); #Break the file contents into lines
	foreach(self::$config as $key => $line){ #Parse every line
	  $line = trim($line);
	  unset(self::$config[$key]);
	  if($line == "" || $line[0] == "#"){ #Ignore empty lines and comments
            continue;
	  }
	  if($line = self::formatLine($line)){
	    self::$config[$line[0]] = $line[1];
	  } else {
	    throw new Exception ("Unexpected format on line: " . $key . "\n");
 	  }
	}

	if(isset(self::$config[$param])){
	  return self::$config[$param];
	}
	return null; 
      }
    } catch (Exception $e){
  	echo $e->getMessage() . "\n";
    } 
  }

  static function formatLine($line = null){
    if(substr_count($line,"=") == 1){ #Only single "=" operator allowed per line
      $line = array_map('trim',explode("=",$line)); #Break the line up into two parts, key and value
      if(isset(self::$translate[$line[1]])){ #Check to see if this string is in our boolean dictionary
	$line[1] = self::$translate[$line[1]];
      } elseif(is_numeric($line[1])){ #If it's numeric, determine if it's a float or an integer
	if(intval($line[1]) != floatval($line[1])){
	  $line[1] = (float)$line[1];
        } else {
	  $line[1] = (int)$line[1];
	}
      }
      return $line;
    }
    return null;
  }
}
?>
