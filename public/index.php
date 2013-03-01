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
require_once "../lib/db.php";

/* PHP init */

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.


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
		$result = $pdo->query("SELECT * FROM $table WHERE source='$key'");
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

	
?>

<!-- Static HTML open tags -->

<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="hs.css">
</head>
<body>
	
<?php
/* PHP main line code */
foreach($categories as $cat){
	display_category($cat);
}
	

?>
<!-- Static HTML closing tags -->
	
</body>
</html>
	
