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
				add_action( 'admin_menu', array($this, 'add_admin_menu') );
				add_action( 'admin_init', array($this, 'settings_init') );
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

				$options = get_option( 'amadamassa_mapa_settings' );
				wp_localize_script('amada-massa-mapa', 'AmadaMassaMapa', array(
						'OPCOES' => array(
							explode(PHP_EOL, $options['textarea_field_0']),
							explode(PHP_EOL, $options['textarea_field_1']),
							explode(PHP_EOL, $options['textarea_field_2']),
							explode(PHP_EOL, $options['textarea_field_3']),
							explode(PHP_EOL, $options['textarea_field_4']),
						),
				    'KML_URL' => $options['text_field_5'],
				));
		}

		public function add_admin_menu(  ) {
			add_options_page( 'Amada Massa - Mapa', 'Amada Massa - Mapa', 'manage_options', 'amada_massa_-_mapa', array($this, 'options_page') );
		}

		public function settings_init(  ) {
			register_setting( 'pluginPage', 'amadamassa_mapa_settings');

			add_settings_section(
				'amadamassa-mapa_amadamassa-mapa_section',
				__( '', 'pluginPage' ),
				array($this, 'settings_section_callback'),
				'pluginPage'
			);

			add_settings_field(
				'textarea_field_0',
				__( 'Área 1', 'pluginPage' ),
				array($this, 'textarea_field_0_render'),
				'pluginPage',
				'amadamassa-mapa_amadamassa-mapa_section'
			);

			add_settings_field(
				'textarea_field_1',
				__( 'Área 2', 'pluginPage' ),
				array($this, 'textarea_field_1_render'),
				'pluginPage',
				'amadamassa-mapa_amadamassa-mapa_section'
			);

			add_settings_field(
				'textarea_field_2',
				__( 'Área 3', 'pluginPage' ),
				array($this, 'textarea_field_2_render'),
				'pluginPage',
				'amadamassa-mapa_amadamassa-mapa_section'
			);

			add_settings_field(
				'textarea_field_3',
				__( 'Área 4', 'pluginPage' ),
				array($this, 'textarea_field_3_render'),
				'pluginPage',
				'amadamassa-mapa_amadamassa-mapa_section'
			);

			add_settings_field(
				'textarea_field_4',
				__( 'Área 5', 'pluginPage' ),
				array($this, 'textarea_field_4_render'),
				'pluginPage',
				'amadamassa-mapa_amadamassa-mapa_section'
			);

			add_settings_field(
				'amadamassa-mapa_text_field_5',
				__( 'URL do mapa (formato KML)', 'pluginPage' ),
				array($this, 'text_field_5_render'),
				'pluginPage',
				'amadamassa-mapa_amadamassa-mapa_section'
			);
		}

		public function textarea_field_0_render(  ) {
			$options = get_option( 'amadamassa_mapa_settings' );
			?>
			<textarea cols='40' rows='5' name='amadamassa_mapa_settings[textarea_field_0]'><?php echo $options['textarea_field_0']; ?></textarea>
			<?php
		}

		public function textarea_field_1_render(  ) {
			$options = get_option( 'amadamassa_mapa_settings' );
			?>
			<textarea cols='40' rows='5' name='amadamassa_mapa_settings[textarea_field_1]'><?php echo $options['textarea_field_1']; ?></textarea>
			<?php
		}

		public function textarea_field_2_render(  ) {
			$options = get_option( 'amadamassa_mapa_settings' );
			?>
			<textarea cols='40' rows='5' name='amadamassa_mapa_settings[textarea_field_2]'><?php echo $options['textarea_field_2']; ?></textarea>
			<?php
		}

		public function textarea_field_3_render(  ) {
			$options = get_option( 'amadamassa_mapa_settings' );
			?>
			<textarea cols='40' rows='5' name='amadamassa_mapa_settings[textarea_field_3]'><?php echo $options['textarea_field_3']; ?></textarea>
			<?php
		}

		public function textarea_field_4_render(  ) {
			$options = get_option( 'amadamassa_mapa_settings' );
			?>
			<textarea cols='40' rows='5' name='amadamassa_mapa_settings[textarea_field_4]'><?php echo $options['textarea_field_4']; ?></textarea>
			<?php
		}


		public function text_field_5_render(  ) {
			$options = get_option( 'amadamassa_mapa_settings' );
			?>
			<input type='text' name='amadamassa_mapa_settings[text_field_5]' value='<?php echo $options['text_field_5']; ?>'>
			<?php
		}


		public function settings_section_callback(  ) {
			echo __( 'Insira nos campos abaixo as opções de entrega/retirada, uma por linha.', 'amadamassa-mapa' );
		}


		public function options_page(  ) {
				?>
				<form action='options.php' method='post'>
					<h2>Amada Massa - Mapa</h2>
					<?php
					settings_fields( 'pluginPage' );
					do_settings_sections( 'pluginPage' );
					submit_button();
					?>
				</form>
				<?php
		}

	}

	$AmadaMassaMapa = new AmadaMassaMapa( __FILE__ );
endif;

?>
