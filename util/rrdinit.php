#!/usr/bin/php
<?php
$path  = dirname(realpath((dirname(__FILE__))));
require_once "../lib/logging.php";
require_once "../lib/init.php";


print "Initializing rrd file: ".$rrdfile."\n";

unlink($rrdfile);

$newrrd = new RRDCreator($rrdfile, "now", 300);


foreach($sources as $s){
	if(isset($config[$s]['graph-type']) && $config[$s]['graph-type'] != 'none'){
		$min = ($config[$s]['min']) ? $config[$s]['min'] : "U";
		$max = ($config[$s]['max']) ? $config[$s]['max'] : "U";
		$newrrd->addDataSource("$s:GAUGE:600:$min:$max");
		print "$s\n";
	}
}
$records = (isset($config['general']['records'])) ? $config['general']['records'] : 288;
$x_factor = (isset($config['general']['x-factor'])) ? $config['general']['x-factor'] : 0.5;
$newrrd->addArchive("LAST:$x_factor:1:$records");
$newrrd->save();

print "Initialization complete\n";
exit( 0 );

?>
