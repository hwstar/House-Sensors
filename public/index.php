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
require_once "../lib/Usermsgs.php";
require_once "../lib/Pagehelper.php";
require_once "../lib/init.php";

Pagehelper::header_no_cache();
Pagehelper::html_open_tags("hs.css", "favicon.ico");	

$db = $config['general']['db'];
$dsn = "sqlite:".$db;


if(false === file_exists($db)){
	Usermsgs::error("Could not find database file!");
	Log::fatal("Database file: ".$db." does not exist");
}

if(time() - filemtime($db) > 70){
	Usermsgs::warning("Stale database file");
	Log::warn("Stale database file. Is $db getting updated?");
}

try{
	$pdo = new PDO($dsn);
}
catch(Exception $e){
	Usermsgs::error("Could not open database file!");
	Log::fatal("Could not open database file ".$config['general']['db'].": ".$e->getMessage());
}




/* PHP Functions */

function display_table($category)
{
	$i = 0;
	global $pdo;
	global $sources;
	global $config;
	global $categories;

	foreach($category as $src){
		$key = $config[$src]['key'];
		$table = $config['general']['table'];
		try{
			$result = $pdo->query("SELECT * FROM $table WHERE source='$key'");
		} catch(Exception $e){
			Usermsgs::warning("Database query failed!");
			Log::warn("Could not query database".$config['general']['db'].": ".$e->getMessage());
		}
		if(false !== $result){
			if(0 === $i){
				$heading = $config[$src]['category'];
				print "<H2>$heading</H2>\n";
				print "<div class=\"CSS_Status_Tables\">\n";
				print "<table>\n";
				print "<tr>\n";
				print "<td>Sensor</td>\n";
				print "<td>Value</td>\n";
				print "</tr>\n";
			}
			
			$i++;			
			printf("<tr>\n");
			$row = $result->fetch(PDO::FETCH_ASSOC);
			printf("<td>%s</td>\n", $config[$src]['description']);
			printf("<td>%s</td>\n",  $row['value']);
			print "</tr>\n";
		}
	}
	if($i){
		print "</table>\n";
		print "</div>\n";
	}
}

function display_category($category)
{
	global $config;
	
	display_table($category);
	foreach($category as $src){
		if($config[$src]['graph-type'] == 'nograph'){
			continue;
		}
		print '<p>';
		print "<img src=\"graph-render.php?source=$src\" />\n";
		print '<p>';
	}
	
}

foreach($categories as $cat){
	display_category($cat);
}

Pagehelper::html_close_tags();

?>
	
