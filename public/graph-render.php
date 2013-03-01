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

require_once "../lib/init.php";
require_once "../lib/logging.php";

$fail = false;

/* Sanity checks */

if(!$_GET || !$_GET['source']){
	warn("Missing source",__FILE__);
	$fail = true;
}

$source=$_GET['source'];


if(!$fail){
	if(!isset($source) || !in_array($source, $sources)){
		warn("Invalid source: ".$source,__FILE__);
		$fail = true;
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
		$fail = true;
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
