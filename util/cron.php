#!/usr/bin/php
<?php
$path  = dirname(realpath((dirname(__FILE__))));
require_once "$path/lib/init.php";
require_once "$path/lib/logging.php";
require_once "$path/lib/db.php";

if(!file_exists($rrdfile)){
	$initscript = $utildir."/rrdinit.php";
	echo `php $initscript`;
	exit( 0 );
}
	
// Update the RRA
try{
	$rra = new RRDUpdater($rrdfile);
} catch(Exception $e){
	fatal("Could not access RRA in file: ".$rrdfile.": ".$e->getMessage(),__FILE__);
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
		fatal("Could not query database: ".$e->getMessage(),__FILE__);
	}
	if($row['value']){
		$v = $row['value'];
		if(isset($config[$src]['scale-function'])){
			$scale_function = $config[$src]['scale-function'];
			$v = eval("return( ".$scale_function." );");
			if($v == FALSE){
				warn("Bad scale function in source ".$src,__FILE__);
				$v = 0;
			}		
		}
		$updates[$src] = $v;
	}
	else{
		warn("key $k returned nothing",__FILE__);
	}
}
if(count($updates) > 0){
	try{
		$rra->update($updates);
	} catch (Exception $e){
		fatal("Could not update rra: ".$e->getMessage(),__FILE__);
	}
}
exit( 0 );

?>




