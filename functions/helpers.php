<?php

function hac_rmr_dir( $dir, $leave_parent = true ) {
	if( is_dir( $dir ) ) {
		$objects = scandir( $dir );
		foreach ( $objects as $object ) {
			if ( $object != "." && $object != ".." ) {
				if ( is_dir( $dir . "/" . $object ) )
					hac_rmr_dir( $dir . "/" . $object );
				else
					unlink( $dir . "/" . $object );
			}
		}
	 	rmdir( $dir );
	}
}

function hac_mkdir_r( $filename ) {
	if( empty( $filename ) || !is_string( $filename ) )
		return false;

	$dirname = dirname( $filename );

	if ( !is_dir( $dirname ) )
		return mkdir( $dirname, 0755, true );

	return false;
}
