<?php

defined( 'ABSPATH' ) || exit();

if( !class_exists( 'hacRevalidate' ) ) :

/**
 *	Class: hacRevalidate,
 */
class hacRevalidate
{
	/**
	 *	@var $file string 			file as resource (use before with fopen())
	 */
	public $file;

	/**
	 *	@var $file_expiry string 	fills datetime string previously optained from file // uses Y-m-d H:i:s format
	 */
	public $file_expiry;

	/**
	 *	@var $current_time string 	current datetime, uses current_time( 'mysql' ) WP functions // uses Y-m-d H:i:s format
	 */
	public $current_time;

	function __construct( $input = false ) {
		if( $input ) {
			$this->file 			= $input;
			$this->file_expiry	= false;
			$this->current_time 	= current_time( 'mysql' );
		}
	}

	/**
	 *	Function: verifies if file is expired or not
	 * --
	 * @return	bool	// true if file is valid and not expired
	 */
	public function verify_file() {
		if( $this->file ) {
			$lines = fread( $this->file, 39 );

			$this->read_date( $lines );

			if( $this->file_expiry && ( $this->file_expiry == "0000-00-00 00:00:00" || $this->current_time < $this->file_expiry ) )
				return true;
		}
		return false;
	}

	/**
	 *	Function: get string datetime from beginning of file
	 * --
	 * @return	bool|string		return datetime string or false if not found
	 */
	public function read_date( $lines ) {
		if( $lines ) {

			$output = array();
			preg_match( '/(?<=HAC_EXPIRY:)\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d/', $lines, $output );
			$output = !empty( $output[0] ) ? $output[0] : '';

			if( !empty( $output ) && ( $output == "0000-00-00 00:00:00" || strtotime( $output ) ) ) {

				$this->file_expiry = $output;

				return $output;
			}
		}
		return false;
	}

	/**
	 *	Static function:
	 * --
	 * @param 	$html				html content to cache
	 * @param 	$expire			expire date
	 *
	 * @return	bool|string		returns modified html with added HAC revalidate information at the beginning of file
	 */
	public static function write_date( $html, $expire ) {
		$pattern = "<!-- HAC_EXPIRY:%s -->";
		if( !empty( $html ) ) {

			if( empty( $expire ) )
				$expire_date = "0000-00-00 00:00:00";
			else if( strtotime( $expire ) )
				$expire_date = date( 'Y-m-d H:i:s', strtotime( $expire ) );
			else
				return false;

			$expiry_line = sprintf( $pattern, $expire_date );

			$output = $expiry_line . "\n";
			$output .= $html;

			return $output;
		}
		return false;
	}
}

endif;
