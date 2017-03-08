<?php

function hac_add( $group, $name, $html, $expire = false ) {
	if( empty( $group ) || empty( $name ) )
		return false;

	return hacFiles::add_file( $group, $name, $html, $expire );
}

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

function hac_delete_file( $group, $name ) {
	if( empty( $group ) || empty( $name ) )
		return false;

	return hacFiles::delete_file( $group, $name, "file" );
}

function hac_delete_group( $group, $name = false ) {

}

function hac_delete_file_pattern( $group, $name ) {

}
