<?php

/**
 *	Class: hacNaming,
 */

defined( 'ABSPATH' ) || exit();

if( !class_exists( 'hacNaming' ) ) :

class hacNaming
{
	public $extension;
	public $glue;

	public $group;
	public $name;
	public $file_group;
	public $file_name;

	public $file;
	public $file_path;
	public $group_is_folder;
	public $group_hierarchy;
	public $group_hierarchy_exceeds;

	function __construct( $group, $name = false ) {
		// settings
		$this->extension 			= HAC_NAME_EXTENSION;
		$this->glue 				= HAC_NAME_GLUE;
		$this->group_is_folder 	= HAC_GROUP_IS_FOLDER;
		$this->group_hierarchy 	= HAC_GROUP_HIERARCHY;
		$this->group_hierarchy_exceeds = false;

		// set name inputs
		$this->group 				= $group;
		$this->name 				= $name;

		// run name generator
		$this->get_name();
	}

	private function get_name() {
		// get name inputs and generate output paths
		$this->formatAndGenerate();
		// build final name from results
		$this->get_filename();
	}

	private function formatAndGenerate() {
		// process group var
		if( !empty( $this->group ) ) {
			if( is_string( $this->group ) )
				$this->file_group = $this->group;
			else if( is_array( $this->group ) )
				$this->file_group = $this->nameFromArray( $this->group, $this->group_is_folder ? "/" : $this->glue, true );
			else
				$this->file_group = false;
		}
		// process name var
		if( !empty( $this->name ) ) {
			if( is_string( $this->name ) )
				$this->file_name = $this->name;
			else if( is_array( $this->name ) )
				$this->file_name = $this->nameFromArray( $this->name );
			else
				$this->file_name = false;
		}

	}

	public function nameFromArray( $input, $glue = false, $is_group = false ) {
		if( empty( $input ) || !is_array( $input ) )
			return false;

		$output 			= "";
		$glue 			= $glue ? $glue : $this->glue;	// set glue (char connector)
		$depth 			= $this->group_hierarchy;			// depth in folder hierarchy
		$input_count 	= count( $input );

		if( $depth && is_numeric( $depth ) && ( $depth < $input_count ) && $is_group ) {

			// get halves
			$folder_part 	= array_slice( $input, 0, $depth );
			$files_part		= array_slice( $input, ( $input_count - $depth ) * (-1) );
			// implode as needed
			$folder_part 	= implode( $folder_part, "/" );
			$files_part		= implode( $files_part, $this->glue );

			$output = $folder_part . "/" . $files_part;
		}
		else {
			if( $is_group && ( $depth !== false && $depth >= $input_count ) ) {
				$this->group_hierarchy_exceeds = true;
			}
			$output 	= implode( $glue, $input );
		}

		if( !empty( $output ) && is_string( $output ) )
			return $output;

		return '';
	}

	private function get_filename() {
		$names 	= array();
		$glue 	= $this->group_is_folder && ( $this->group_hierarchy === false || $this->group_hierarchy_exceeds ) ? "/" : $this->glue;

		if( $this->file_group )
			$names[] = $this->file_group;
		if( $this->file_name )
			$names[] = $this->file_name;

		if( !empty( $names ) && is_array( $names ) )
			$this->file = implode( $glue, $names );
		else
			$this->file = false;
	}

	private function get_filepath() {

	}
}

endif;
