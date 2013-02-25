<?php

$sitedir = dirname(realpath((dirname(__FILE__))));
$utildir = $sitedir."/util";
$cfgloc = $sitedir."/conf";
$dataloc = $sitedir."/data";
$rrdfile = $dataloc."/hs.rrd";
$logfile = $sitedir."/log/hs.log";

$config = parse_ini_file($cfgloc."/hs.conf", 1);

/* In no config file, exit */

if(!$config){
	error_log("init.php: Can't read config file $cfgloc/hs.conf");
}

if(!array_key_exists("general", $config)){
	error_log("init.php: Config file missing general section");
}

/* Set general defaults */
if(!isset($config['general']['db']))
	$config['general']['db'] = $dataloc."/data.db";

if(!isset($config['general']['records'])){
	$config['general']['records'] = 288;
}

if(!isset($config['general']['x-factor'])){
	$config['general']['x-factor'] = 0.5;
}

if(!isset($config['general']['table'])){
	$config['general']['table'] = 'trigvars';
}

if(!isset($config['general']['graph-width'])){
		$config['general']['graph-width']= 400;
}	

if(!isset($config['general']['graph-height'])){
		$config['general']['graph-height'] = 100;
}

// Get source keys
$sources = array();
$categories = array();
foreach(array_keys($config) as $k){
	if(strcmp("general", $k)){
		// Set source defaults
		
		if(!isset($config[$k]['category'])){
			$config[$k]['category'] = "Uncategorized";
		}		
		
		if(!isset($config[$k]['description'])){
			$config[$k]['description'] = "Default Description";
		}
		
		if(!isset($config[$k]['graph-title'])){
			$config[$k]['graph-title'] = $config[$k]['description'];
		}
		
		if(!isset($config[$k]['graph-legend'])){
			$config[$k]['graph-legend'] = $config[$k]['graph-title'];
		}
		
		if(!isset($config[$k]['last-numeric-format'])){
			$config[$k]['last-numeric-format'] = "2.1lf";
		}
		
		if(!isset($config[$k]['graph-type'])){
			$config[$k]['graph-type'] = "nograph";
		}
		
		if(!isset($config[$k]['color'])){
			$config[$k]['color'] = "00FF00";
		}
		
		if(!isset($config[$k]['units'])){
			$config[$k]['units'] = "U,Units";
		}
		
		if(!isset($config[$k]['min'])){
			$config[$k]['min'] = "U";
		}
		
		if(!isset($config[$k]['max'])){
			$config[$k]['max'] = "U";
		}
		
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
