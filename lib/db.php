<?php

$db = $config['general']['db'];

if(!file_exists($db)){
	fatal("Database file: ".$db." does not exist",__FILE__);
}

$dsn = "sqlite:".$db;

try{
	$pdo = new PDO($dsn);
}
catch(Exception $e){
	fatal("Could not open database file ".$config['general']['db'].": ".$e->getMessage(),__FILE__);
}
?>
