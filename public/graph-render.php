<?php

require_once "../lib/init.php";
require_once "../lib/logging.php";

$fail = 0;

/* Sanity checks */

if(!$_GET || !$_GET['source']){
	warn("Missing source",__FILE__);
	$fail = 1;
}

$source=$_GET['source'];


if(!$fail){
	if(!isset($source) || !in_array($source, $sources)){
		warn("Invalid source: ".$source,__FILE__);
		$fail = 1;
	}
}

if(!$fail){
	/* Set defaults */


	$units = explode(",", $config[$source]['units'], 2);
	if(!$units[1]){
		$units[1] = $units[0];
	}
	
	$width = $config['general']['graph-width'];
	
	$height = $config['general']['graph-height'];	

	$description = $config[$source]['description'];
	
	$graph_title = $config[$source]['graph-title'];
	
	$legend = $config[$source]['graph-legend'];
		
	$last_numeric_format = $config[$source]['last-numeric-format'];

	$color = $config[$source]['color'];
	

	/* Create graph options array */

	$graphoptions = array(
	"--vertical-label" => $units[1],
	"--title" => $graph_title,
	"--width" => $width,
	"--height" => $height,
	"DEF:graph"."=".$rrdfile.":".$source.":".'LAST',
	"LINE1:graph"."#".$color.":".$legend,
	"GPRINT:graph".":".'LAST'.":"."Last Value\\".":%".$last_numeric_format." ".$units[0]
	);

	/* Generate graph */
	try{
		$graph = new RRDGraph("-"); // output to array
		$graph->setOptions($graphoptions);
		$output = $graph->saveVerbose();
	} catch(Exception $e){
		$fail = 1;
		warn("Could not generate graph for: $source, ".$e->getMessage(),__FILE__);
	}
}
  

/* Output graph */

if(!$fail){
	header("Content-Type: image/png");
	echo $output['image'];
}
else{
	print "graph_render.php: Could not display graph";
}

 
exit;
?>
