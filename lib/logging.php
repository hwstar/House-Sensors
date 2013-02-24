<?php
// Log fatal error and exit

function fatal($emsg, $module = "?")
{	
	global $logfile;
	$dt = strftime("%c");
	error_log($dt." Fatal($module): ".$emsg."\n");
	exit;
}

// Log warning and continue

function warn($wmsg, $module = "?")
{
	global $logfile;
		$dt = strftime("%c");
	error_log($dt." Warning($module): ".$wmsg."\n");
}
?>
