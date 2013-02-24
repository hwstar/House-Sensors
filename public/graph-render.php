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
	if(!$source || !in_array($source, $sources)){
		warn("Invalid source: ".$source,__FILE__);
		$fail = 1;
	}
}

if(!$fail){
	/* Set defaults */

	if(!isset($config[$source]['units'])){
		$units[0] = "U";
		$units[1] = "Units";
	}
	else{
		$units = explode(",", $config[$source]['units'], 2);
		if(!$units[1]){
			$units[1] = $units[0];
		}
	}

	if(!isset($config[$source]['description'])){
		$description = "Default Description";
	}
	else{
		$description = $config[$source]['description'];
	}
	
	if(!isset($config[$source]['graph-title'])){
		$graph_title = $description;
	}
	else{
		$graph_title = $config[$source]['graph-title'];
	}

	if(!isset($config[$source]['graph-legend'])){
		$legend = $graph_title;
	}
	else{
		$legend = $config[$source]['graph-legend'];
	}	


	if(isset($config[$source]['last-numeric-format'])){
		$last_numeric_format = $config[$source]['last-numeric-format'];
	}
	else{
		$last_numeric_format = "2.1lf";
	}

	if(!isset($config[$source]['color'])){
		$color = "00FF00";
	}
	else{
		$color = $config[$source]['color'];
	}

	/* Create graph options array */

	$graphoptions = array(
	"--vertical-label" => $units[1],
	"--title" => $graph_title,
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
