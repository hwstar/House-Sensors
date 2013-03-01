

#!/usr/bin/php
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

$path  = dirname(realpath((dirname(__FILE__))));
require_once "../lib/logging.php";
require_once "../lib/init.php";


print "Initializing rrd file: ".$rrdfile."\n";

unlink($rrdfile);

$newrrd = new RRDCreator($rrdfile, "now", 300);


foreach($sources as $s){
	if($config[$s]['graph-type'] != 'nograph'){
		$min = $config[$s]['min'];
		$max = $config[$s]['max'];
		$newrrd->addDataSource("$s:GAUGE:600:$min:$max");
		print "$s\n";
	}
}
$records = $config['general']['records'];
$x_factor = $config['general']['x-factor'];
$newrrd->addArchive("LAST:$x_factor:1:$records");
$newrrd->save();

print "Initialization complete\n";
exit( 0 );

?>
