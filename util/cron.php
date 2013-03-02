#!/usr/bin/php
<?php

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 */

$path  = dirname(realpath((dirname(__FILE__))));

require_once "$path/lib/Log.php";
require_once "$path/lib/init.php";

$db = $config['general']['db'];
$dsn = "sqlite:".$db;


if(false === file_exists($db)){
	Log::fatal("Database file: ".$db." does not exist");
}

try{
	$pdo = new PDO($dsn);
}
catch(Exception $e){
	Log::fatal("Could not open database file ".$config['general']['db'].": ".$e->getMessage());
}


if(false === file_exists($rrdfile)){
	$initscript = $utildir."/rrdinit.php";
	echo `php $initscript`;
	exit( 0 );
}
	
// Update the RRA
try{
	$rra = new RRDUpdater($rrdfile);
} catch(Exception $e){
	Log::fatal("Could not access RRA in file: ".$rrdfile.": ".$e->getMessage());
}

$updates = array();
foreach($sources as $src){
	$key = $config[$src]['key'];
	$table = $config['general']['table'];
	
	if($config[$src]['graph-type'] != 'standard'){
		continue;
	}
	
	
	$sth = $pdo->prepare("SELECT * FROM $table WHERE source=?");
	
	try{
		$sth->execute(array($key));
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
	} catch (Exception $e){
		Log::fatal("Could not query database: ".$e->getMessage());
	}
	if(isset($row['value'])){
		$v = $row['value'];
		if(isset($config[$src]['scale-function'])){
			$scale_function = $config[$src]['scale-function'];
			$v = eval("return( ".$scale_function." );");
			if(false === $v){
				Log::warn("Bad scale function in source ".$src);
				$v = 0;
			}		
		}
		$updates[$src] = $v;
	}
	else{
		Log::warn("key $k returned nothing");
	}
}
if(count($updates) > 0){
	try{
		$rra->update($updates);
	} catch (Exception $e){
		Log::fatal("Could not update rra: ".$e->getMessage());
	}
}
exit( 0 );

?>




