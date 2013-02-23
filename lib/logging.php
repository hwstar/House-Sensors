<?php
// Log fatal error and exit

function fatal($emsg)
{	
	global $logfile;
	error_log("Fatal: ".$emsg."\n", 3, $logfile);
	exit;
}

// Log warning and continue

function warn($wmsg)
{
	global $logfile;
	error_log("Warning: ".$wmsg."\n", 3, $logfile);
}
?>
