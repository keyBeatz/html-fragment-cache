<?php

defined( 'ABSPATH' ) || exit();

if( !class_exists( 'hacFiles' ) ) :

/**
 *	Class: hacFiles, core class used to manipulation with stored html files.
 *	It mediate saving, deleting and loading stored html files.
 */
class hacFiles
{
	/**
	 *	@var	$cache_enabled	bool		false for disable cache
	 */
	public static $cache_enabled 	= true;

	/**
	 *	Static function: get specific file based on group and name
	 *	--
	 *	@param $group	array|string	finds path or folder name from string or array (more info in files.php class)
	 *	@param $name	array|string	finds path or file name from string or array (more info in files.php class)
	 *	@param $args	array 			additional settings
	 *
	 *	@return string|bool - false when file not found or expired
	 */
	public static function get_file( $group, $name = false, $args = array() ) {
		if( !self::$cache_enabled || !defined( 'HAC_CACHE_DIR' ) )
			return false;

		// generate name
		$name = new hacNaming( $group, $name );

		if( !empty( $name->file ) ) {
			extract( $args, EXTR_SKIP );
			// get file path
			$file_path = HAC_CACHE_DIR . $name->file . $name->extension;
			// if file exists check if is valid and return its contents
			if( file_exists( $file_path ) ) {
				$file = fopen( $file_path, "r+" );
				$revalidate = new hacRevalidate( $file );

				if( $revalidate->verify_file() ) {
					$contents = stream_get_contents( $file );
					if( !empty( $contents ) )
						return $contents;
				}
			}
		}
		return false;
	}

	/**
	 *	Static function: add file and crate cache
	 *	--
	 *	@param $group	array|string	finds path or folder name from string or array (more info in files.php class)
	 *	@param $name	array|string	finds path or file name from string or array (more info in files.php class)
	 *	@param $html	string 			html content for save
	 *	@param $expire	string|bool 	date in mysql format or false for infinite cache
	 *
	 *	@return bool - true when successfully saved, false when false
	 */
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
			// if group is folder setting is set, check if folder tree exists, if not create it
			if( $name->group_is_folder )
				hac_mkdir_r( $file_path );

			$dir = is_dir( $file_dir_path );

			// if folder was successfully created or exists
			if( $dir && !empty( $html ) ) {

				// overwrite or create the file
				$file = fopen( $file_path, "w" );
				if( $file ) {

					// add HAC revalidate information at the beginning of file
					$html = hacRevalidate::write_date( $html, false );

					// put contents of $html var into the file and close
					fwrite( $file, $html );
					fclose( $file );

					return true;
				}
			}
		}
		return false;
	}

	/**
	 *	Static function: delete file(s) or group (folders)
	 *	--
	 *	@param $group	array|string	finds path or folder name from string or array (more info in files.php class)
	 *	@param $name	array|string	finds path or file name from string or array (more info in files.php class)
	 *	@param $delete	string 			type file|group
	 *
	 *	@return void
	 */
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
					if( HAC_GROUP_IS_FOLDER ) {
						$delete_path = HAC_CACHE_DIR . $name->file_group;
						hac_rmr_dir( $delete_path );
					}
					else {
						$delete_path = HAC_CACHE_DIR . $name->file_group;
						foreach( glob( $delete_path . "*" ) as $file_to_delete ) {
							unlink( $file_to_delete );
						}
					}
					break;
			}
		}
	}
}

endif;
