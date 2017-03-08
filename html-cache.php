<?php
/*
Plugin Name: HTML fragment cache
Description: HTML fragment cache
Version: 0.1
*/

defined( 'ABSPATH' ) || exit();

/**
 *	Constant: Directory where cached files will be stored.
 */
define( "HAC_CACHE_DIR", ABSPATH . "wp-content/uploads/hac_cache/" );

/**
 *	Constant: Choose if you want group variable behave as folder (thus create and store cached files a folder tree).
 *	While false keeps storing files in HAC_CACHE_DIR (cache root) in basic format (without folders).
 *	--
 *	@var	bool
 */
define( "HAC_GROUP_IS_FOLDER", true );

/**
 *	Constant: used only when const HAC_GROUP_IS_FOLDER is true.
 *	False for infinite folder tree, or input a number to set max hierarchy level.
 *
 *	E.g.: if you input array in $group ( count( $group ) === 5 ) and set HAC_GROUP_HIERARCHY === 2, only 2 levels of folder tree will be created and rest will be treated as name of the file (like prefix)
 *	--
 *	@var 	bool|int
 */
define( "HAC_GROUP_HIERARCHY", 2 );

/**
 *	Constant: Set extension for cached files
 *	--
 *	@var	string
 */
define( "HAC_NAME_EXTENSION", ".html" );

/**
 *	Constant: Glue (connector character(s)) used in implode functions for enerating file names from array input (used in both $group and $name vars)
 *	--
 *	@var	string
 */
define( "HAC_NAME_GLUE", "_" );

if( !class_exists( 'hacPlugin' ) ) :

class hacPlugin
{
	private $plugin_dir;

	/**
	 * Initialize plugin
	 */
	public static function init() {
	  $class = __CLASS__;
	  new $class;
	}

	function __construct() {
		// set vars
		$this->plugin_dir = plugin_dir_path( __FILE__ );

		// bootstrap the plugin
		$this->bootstrap();
	}

	function bootstrap() {
		// core classes
		require_once( $this->plugin_dir . "core/classes/files.php" );
		require_once( $this->plugin_dir . "core/classes/naming.php" );
		require_once( $this->plugin_dir . "core/classes/revalidate.php" );
		// api & helpers
		require_once( $this->plugin_dir . "core/api.php" );
		require_once( $this->plugin_dir . "functions/helpers.php" );
	}
}

add_action( 'plugins_loaded', array( 'hacPlugin', 'init' ) );

endif;
