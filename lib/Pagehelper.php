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
class Pagehelper
{
	/* Send no cache headers */
	static function header_no_cache()
	{
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
		header('Pragma: no-cache'); // HTTP 1.0.
		header('Expires: 0'); // Proxies.
	}
	
	/* Send png header */
	
	static function header_png()
	{
		header("Content-Type: image/png");
	}
	
	/* Send open tags */
	
	static function html_open_tags($css = NULL, $favicon = NULL)
	{
		ob_start();
		printf("<!DOCTYPE html>\n");
		printf("<html>\n");
		printf("<head>\n");
		if(isset($favicon)){
			printf("<link rel=\"shortcut icon\"  href=\"/$favicon\" type=\"image/x-icon\">\n");
			printf("<link rel=\"icon\" href=\"/$favicon\" type=\"image/x-icon\">\n");
		}
		if(isset($css)){
			printf("<link rel=\"stylesheet\" type=\"text/css\" href=\"$css\">\n");
		}
		printf("</head>\n");
		printf("<body>\n");
	}
	
	/* Send close tags */
	
	static function html_close_tags()
	{
		printf("</body>\n");
		printf("</html>\n");
	}
		
}



?>
