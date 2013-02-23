<?php

$sitedir = dirname(realpath((dirname(__FILE__))));
$utildir = $sitedir."/util";
$cfgloc = $sitedir."/conf";
$dataloc = $sitedir."/data";
$rrdfile = $dataloc."/hs.rrd";
$logfile = $sitedir."/log/hs.log";

$config = parse_ini_file($cfgloc."/hs.conf", 1);

// In no config file, exit
if(!$config){
	error_log("Can't read config file $cfgloc/hs.conf");
}

if(!array_key_exists("general", $config)){
	fatal("Config file missing general section");
}
// Get source keys
$sources = array();
$categories = array();
foreach(array_keys($config) as $k){
	if(strcmp("general", $k)){
		// Push to source list
		array_push($sources, $k);
		// Add source category list
		if(!array_key_exists($config[$k]['category'],$categories)){
			$categories[$config[$k]['category']] = array($k);
		}
		else{
			array_push($categories[$config[$k]['category']], $k);
		}	
	}
}


?>
