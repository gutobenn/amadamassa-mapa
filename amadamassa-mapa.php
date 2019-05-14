<?php
/**
 * Plugin Name: Amada Massa Mapa
 * Description:
 * Author: Amada Massa
 * Version: 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'AmadaMassaMapa' ) ) :

	class AmadaMassaMapa {
		/**
		 * Construct the plugin.
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'init' ) );
      add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
		/**
		 * Initialize the plugin.
		 */
		public function init() {
				require_once( 'includes/class-amada-massa-mapa-address-field.php' );
				require_once( 'includes/class-amada-massa-mapa-area-field.php' );

				add_filter( 'ninja_forms_register_fields', array($this, 'register_fields'));
        add_filter( 'ninja_forms_field_template_file_paths', array( $this, 'register_template_path' ) );
		}

		public function register_fields($actions) {
	    $actions['amada_massa_endereco'] = new AmadaMassaMapa_AddressField();
			$actions['amada_massa_area'] = new AmadaMassaMapa_AreaField();
	    return $actions;
		}

		public function register_template_path( $paths ) {
				$paths[] = plugin_dir_path(__FILE__) . 'includes/Templates/';
				return $paths;
		}

		public function enqueue_scripts(){
				wp_enqueue_script( 'gmaps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCb6l3pC5to8pGMkTIy72uzj3S4FQ3D67s&libraries=places&language=pt&region=BR' );
				wp_enqueue_script( 'amada-massa-mapa', plugin_dir_url(__FILE__) . 'js/mapa.js', array( 'nf-front-end' ) );
				wp_enqueue_script( 'amada-massa-geoxml3', plugin_dir_url(__FILE__) . 'js/geoxml3.js', array( 'nf-front-end' ) );
				wp_localize_script('amada-massa-mapa', 'AmadaMassaMapa', array(
				    'KML_URL' => plugin_dir_url(__FILE__) . 'mapa.kml',
				));
		}
	}

	$AmadaMassaMapa = new AmadaMassaMapa( __FILE__ );
endif;

?>
