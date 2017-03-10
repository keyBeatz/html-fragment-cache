<?php

/**
 *	Function: adds file to cache
 *	--
 *	@param $group	array|string	sets path or folder name in string or array (more info in files.php class)
 *	@param $name	array|string	sets path or file name in string or array (more info in files.php class)
 *	@param $html	string			html string, content to save
 *	@param $expire	string			datetime string in mysql format
 *
 *	@return string|bool - false when file not found or expired
 */
function hac_add( $group, $name, $html, $expire = false ) {
	if( empty( $group ) || empty( $name ) )
		return false;

	return hacFiles::add_file( $group, $name, $html, $expire );
}

/**
 *	Function: adds file to cache
 *	--
 *	@param $group	array|string	gets path or folder name from string or array (more info in files.php class)
 *	@param $name	array|string	gets path or file name from string or array (more info in files.php class)
 *	@param $echo	bool				true for direct echoing the output, false for return content as string
 *
 *	@return string|bool - false when file not found or expired
 */
function hac_get( $group = false, $name = false, $echo = false ) {
	if( !$group && !$name ) {

	}
	else {
		$html = hacFiles::get_file( $group, $name );

		if( empty( $html ) )
			return false;

		if( $echo )
			echo $html;
		else
			return $html;
	}
}

/**
 *	Function: used for deleting specific folder
 *	--
 *	@param $group	array|string	finds path or folder name from string or array (more info in files.php class)
 *	@param $name	array|string	finds path or file name from string or array (more info in files.php class)
 *	@param $echo	bool				true for direct echoing the output, false for return content as string
 *
 *	@return string|bool - false when file not found or expired
 */
function hac_delete_file( $group, $name ) {
	if( empty( $group ) || empty( $name ) )
		return false;

	return hacFiles::delete_file( $group, $name, "file" );
}

/**
 *	Function: used for deleting whole folders or "prefixed" files
 *	--
 *	@param $group	array|string	finds path or folder name from string or array (more info in files.php class)
 *	@param $name	array|string	finds path or file name from string or array (more info in files.php class)
 *	@param $echo	bool				true for direct echoing the output, false for return content as string
 *
 *	@return string|bool - false when file not found or expired
 */
function hac_delete_group( $group, $name = false ) {
	if( empty( $group ) )
		return false;

	return hacFiles::delete_file( $group, $name, "group" );
}

/**
 *	Function: delete all cache files
 */
function hac_flush() {
	if( defined( 'HAC_CACHE_DIR' ) )
		hac_rmr_dir( HAC_CACHE_DIR );
}
