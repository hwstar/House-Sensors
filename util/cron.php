<?php

function fatal($emsg)
{
	error_log("Fatal: ".$emsg);
	exit;
}

function warn($msg)
{
	error_log("Warning: ".$wmsg);
}
	

$mydir =  dirname(__FILE__);

$cfgloc = $mydir."/../conf";
$rrdloc = $mydir."/../graphs";

$config = parse_ini_file($cfgloc."/hs.conf", 1);

// In no config file, exit
if(!$config){
	fatal("No config file");
}

if(!array_key_exists("general", $config)){
	fatal("Config file missing general section");
}
// Get source keys
$sources = array();
foreach(array_keys($config) as $k){
	if(strcmp("general", $k)){
		array_push($sources, $k);
	}
}


// Exit if no sources
if(count($sources) < 1){
	fatal("No sources defined");
}

	
?>
