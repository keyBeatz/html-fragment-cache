<?php
/**
 *	Class: hacFiles, core class used to manipulation with stored html files.
 *	It mediate saving, deleting and loading stored html files.
 */

defined( 'ABSPATH' ) || exit();

if( !class_exists( 'hacFiles' ) ) :

class hacFiles
{
	public static $cache_enabled 	= true;

	public static function get_file( $group, $name = false, $args = array() ) {
		if( !self::$cache_enabled || !defined( 'HAC_CACHE_DIR' ) )
			return false;

		// generate name
		$name = new hacNaming( $group, $name );

		if( !empty( $name->file ) ) {
			extract( $args, EXTR_SKIP );
			// get file path
			$file_path = HAC_CACHE_DIR . $name->file . $name->extension;
			// if file exists, return its contents
			if( file_exists( $file_path ) )
				return file_get_contents( $file_path );
			else
				return '';
		}
		return false;
	}

	public static function add_file( $group, $name = false, $html = '', $expire = false ) {
		if( !self::$cache_enabled || !defined( 'HAC_CACHE_DIR' ) )
			return false;

		// generate name
		$name = new hacNaming( $group, $name );

		if( !empty( $name->file ) ) {
			// get file path
			$file_path 		= HAC_CACHE_DIR . $name->file . $name->extension;
			// get file cache dir
			$file_dir_path = dirname( $file_path );
			// create folder if not already exists
			//$dir 				= !$name->is_group_folder ? is_dir( $file_dir_path ) : is_dir( $file_dir_path ) || mkdir( $file_dir_path );

			// if group is folder setting is set, check if folder tree exists, if not create it
			if( $name->is_group_folder )
				hac_mkdir_r( $file_path );

			$dir = is_dir( $file_dir_path );

			// if folder was successfully created or exists
			if( $dir && !empty( $html ) ) {

				// overwrite or create the file
				$file = fopen( $file_path, "w" );
				if( $file ) {
					// put contents of $html var into that file
					fwrite( $file, $html );
					fclose( $file );

					return true;
				}
			}
		}
		return false;
	}

	public static function delete_file( $group, $name = false, $delete = "file" ) {
		if( !self::$cache_enabled || !defined( 'HAC_CACHE_DIR' ) || empty( $delete ) )
			return false;

		$deleted 		= false;
		$delete_path 	=	"";

		// generate name
		$name = new hacNaming( $group, $name );
		if( !empty( $name->file ) ) {
			switch( $delete ) {
				case "file":
					$delete_path = HAC_CACHE_DIR . $name->file . $name->extension;
					// delete file if exists
					if( file_exists( $delete_path ) )
						unlink( $delete_path );
					break;
				case "group":

					break;
				case "pattern":

					break;
			}
		}
	}
}

endif;
