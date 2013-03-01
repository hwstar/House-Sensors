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

$db = $config['general']['db'];

if(false === file_exists($db)){
	fatal("Database file: ".$db." does not exist",__FILE__);
}

$dsn = "sqlite:".$db;

try{
	$pdo = new PDO($dsn);
}
catch(Exception $e){
	fatal("Could not open database file ".$config['general']['db'].": ".$e->getMessage(),__FILE__);
}
?>
