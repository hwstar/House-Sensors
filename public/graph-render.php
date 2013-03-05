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
require_once "../lib/Log.php";
require_once "../lib/Pagehelper.php";
require_once "../lib/init.php";

$fail = false;



/* Sanity checks */

if(!isset($_GET) || !isset($_GET['source'])){
	Log::warn("Missing source");
	$fail = true;
	$source = "";
}
else{
	$source=$_GET['source'];
}


if(false === $fail){
	if(!isset($source) || !in_array($source, $sources)){
		Log::warn("Invalid source: ".$source);
		$fail = true;
	}
}

if(false === $fail){
	/* Set defaults */


	$units = explode(",", $config[$source]['units'], 2);
	if(!isset($units[1])){
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
		Log::warn("Could not generate graph for: $source, ".$e->getMessage());
	}
}
  

/* Output graph */
Pagehelper::header_png();
if(false === $fail){
	echo $output['image'];
}
else{
	/* Generate an error image */
	
	$canvas = imagecreate( 400, 100 );
	$red = imagecolorallocate( $canvas, 255, 0, 0 );
	$white = imagecolorallocate( $canvas, 255, 255, 255 );

	imagefilledrectangle( $canvas, 9, 9, 389, 89, $white );

	$font = "/usr/share/fonts/truetype/freefont/FreeSansOblique.ttf";
	$text = "Graph Error ($source), check log";
	$size = "15";

	$box = imageftbbox( $size, 0, $font, $text );
	$x = (400 - ($box[2] - $box[0])) / 2;
	$y = (100 - ($box[1] - $box[7])) / 2;
	$y -= $box[7];

	imageTTFText( $canvas, $size, 0, $x, $y, $red, $font, $text );
	
	imagepng( $canvas, NULL);

	imagedestroy( $canvas ); 
	
}

 
exit;
?>
