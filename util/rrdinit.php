#!/usr/bin/php
<?php
$path  = dirname(realpath((dirname(__FILE__))));
require_once "../lib/logging.php";
require_once "../lib/init.php";


print "Initializing rrd file: ".$rrdfile."\n";

unlink($rrdfile);

$newrrd = new RRDCreator($rrdfile, "now", 300);


foreach($sources as $s){
	$min = ($config[$s]['min']) ? $config[$s]['min'] : "U";
	$max = ($config[$s]['max']) ? $config[$s]['max'] : "U";
	$newrrd->addDataSource("$s:GAUGE:600:$min:$max");
	print "$s\n";
}
$records = ($config[$s]['records']) ? $config[$s]['records'] : 288;
$x_factor = ($config[$s]['x-factor']) ? $config[$s]['x-factor'] : 0.5;
$newrrd->addArchive("LAST:$x_factor:1:$records");
$newrrd->save();

print "Initialization complete\n";
exit( 0 );

?>
