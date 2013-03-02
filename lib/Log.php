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

class Log{
	
	// Log fatal error and exit

	static public function fatal($emsg)
	{	
		$bt =  debug_backtrace();
		self::_log($emsg, "Fatal", $bt); 
		exit ( 1 );
	}
	
	// Log warning and continue
	
	static public function warn($wmsg)
	{
		$bt =  debug_backtrace();
		self::_log($wmsg, "Warning", $bt); 
	}
	
	// Log note and continue
	
	static public function note($nmsg)
	{
		
		self::_log($nmsg, "Note"); 
	}
	
	static private function _log($msg, $type, $bt = NULL)
	{
		if(isset($bt)){
			$cf =  'File: '. $bt[0]['file'] . ' Line:  '. $bt[0]['line'];
			error_log("$type($cf): $msg ");
		}
		else{
			error_log("$type: $msg ");
		}
	}

}
?>
