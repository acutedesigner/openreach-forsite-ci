<?php
/**
 * @addtogroup MPTtree
 * @{
 */
/**
 * An extended loader to enable users to init multiple instances of MPTtree with different tables and names without the need to extend the class.
 */
class MY_Loader extends CI_Loader{
 	/**
 	 * Constructor, calls parent constructor.
 	 */
	function __construct()
	{
		parent::__construct();
 	}
 	
	/**
	 * @addtogroup MPTtree
	 * @{
	 */
	/*
	    This file is part of MPTtree.

	    MPTtree is free software; you can redistribute it and/or modify
	    it under the terms of the GNU Lesser General Public License as published by
	    the Free Software Foundation; either version 3 of the License, or
	    (at your option) any later version.

	    MPTtree is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU Lesser General Public License for more details.

	    You should have received a copy of the GNU Lesser General Public License
	    along with this program.  If not, see <http://www.gnu.org/licenses/>.
	 */
 	/**
 	 * Loads the MPTtree model with the specified table, and stores the instance under $name (or $table, if name is omitted).
 	 * If you have your own extended loader, just copy this method to yours (hopefully everything will work)
 	 * @since 0.1.5
 	 * @param $table The table that MPTtree shall use
 	 * @param $name The name of the (becoming) model, defaults to $table
 	 * @param $options The options passed to set_opts(), empty array if to use defaults
 	 */
 	function mptt($table, $name = null, $options = array()){
 		if($table == ''){
 			show_error('MPTtree: An empty string as table name was recieved, cannot instantiate MPTtree.');
 			return;
 		}
 		// if name is null or empty, set name to tablename
 		if($name == null || $name == '')
 			$name = $table;
 		
 		$CI =& get_instance();
		
		$CI->load->model('MPTtree',$name);
		$options['table'] = $table;
		$CI->$name->set_opts($options);
 	}
 	/**
 	 * @}
 	 */
}
/**
 * @}
 */
?>