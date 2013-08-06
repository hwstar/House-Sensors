#!/usr/bin/php
<?php

$path  = dirname(realpath((dirname(__FILE__))));
require_once "$path/lib/init.php";


print "Initializing rrd file: ".$rrdfile."\n";

unlink($rrdfile);

$newrrd = new RRDCreator($rrdfile, "now", 300);


foreach($sources as $s){
	if($config[$s]['graph-type'] != 'nograph'){
		$min = $config[$s]['min'];
		$max = $config[$s]['max'];
		$newrrd->addDataSource("$s:GAUGE:600:$min:$max");
		print "$s\n";
	}
}
$records = $config['general']['records'];
$x_factor = $config['general']['x-factor'];
$newrrd->addArchive("LAST:$x_factor:1:$records");
$newrrd->save();

print "Initialization complete\n";
exit( 0 );

?>
