<?php

function fatal($emsg)
{
	error_log("Fatal: ".$emsg);
	exit;
}

function warn($msg)
{
	error_log("Warning: ".$wmsg);
}

$datadir = dirname(__FILE__)."/../data";  
$graph=$_GET['graph'];
	
	
/* Sanity checks */
	
if(!$graph){
	fatal("No graph name passed in get method");
}
	
$file_parts = pathinfo($graph);	
if(strcmp($file_parts['extension'], "png")){
	fatal("Incorrect extension type");
}
	
if(strlen($file_parts['directory'])){
	fatal("Directory name not allowed");
}
	
if(!file_exists($datadir."/".$graph)){
	fatal("Graph file does not exist");
}
		
    /* Attempt to open file */
$fp = fopen($datadir."/".$graph, 'rb'); // stream the image directly from the file
  
if(!$fp){
	fatal("Could not open graph file");
}
  
/* Output graph */
header("Content-Type: image/png");
  
fpassthru($fp);
close($fp);
 
exit;
?>
