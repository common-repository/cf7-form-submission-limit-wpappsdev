<?php
/**
 * Plugin Name:       WPAppsDev - CF7 Form Submission Limit
 * Description:       Contact Form 7 form submission limit control plugin.
 * Version:           2.4.0
 * Author:            Saiful Islam Ananda
 * Author URI:        https://saifulananda.me/
 * License:           GNU General Public License v2 or later
 * Text Domain:       wpappsdev-submission-limit-cf7
 * Domain Path:       /languages
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * WPAppsDev_CF7_Limit class.
 *
 * @class WPAppsDev_CF7_Limit The class that holds the entire WPAppsDev_CF7_Limit plugin
 */
final class WPAppsDev_CF7_Limit {
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '2.4.0';

	/**
	 * Constructor for the WPAppsDev_CF7_Limit class.
	 *
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

		$this->init_appsero_tracker();

		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
	}

	/**
	 * Initializes the WPAppsDev_CF7_Limit() class.
	 *
	 * Checks for an existing WPAppsDev_CF7_Limit() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define the required plugin constants.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'WPADCF7SL', __FILE__ );
		define( 'WPADCF7SL_NAME', 'wpappsdev-submission-limit-cf7' );
		define( 'WPADCF7SL_VERSION', $this->version );
		define( 'WPADCF7SL_DIR', trailingslashit( plugin_dir_path( WPADCF7SL ) ) );
		define( 'WPADCF7SL_URL', trailingslashit( plugin_dir_url( WPADCF7SL ) ) );
		define( 'WPADCF7SL_ASSETS', trailingslashit( WPADCF7SL_URL . 'assets' ) );
	}

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_plugin() {
		if ( ! self::check_cf7_exist() ) {
			add_action( 'admin_notices', [ $this, 'activation_notice' ] );

			return;
		}

		// Localize our plugin
		add_action( 'init', [ $this, 'localization_setup' ] );

		new WPAppsDev\CF7SL\Assets();
		new WPAppsDev\CF7SL\Cron();

		if ( is_admin() ) {
			new WPAppsDev\CF7SL\Admin();
		} else {
			new WPAppsDev\CF7SL\Frontend();
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new WPAppsDev\CF7SL\Ajax();
		}
	}

	/**
	 * Initialize plugin for localization.
	 *
	 * @uses load_plugin_textdomain()
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'wpappsdev-submission-limit-cf7', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Do stuff upon plugin activation.
	 *
	 * @return void
	 */
	public function activate() {
		$installed = get_option( 'wpadcf7sl_installed' );

		if ( ! $installed ) {
			update_option( 'wpadcf7sl_installed', time() );
		}

		update_option( 'wpadcf7sl_version', WPADCF7SL_VERSION );

		if ( ! wp_next_scheduled( 'wpadcf7sl_submission_count_reset' ) ) {
			wp_schedule_event( time(), 'daily', 'wpadcf7sl_submission_count_reset' );
		}
	}

	/**
	 * Do stuff upon plugin deactivation.
	 *
	 * @return void
	 */
	public function deactivate() {
		wp_clear_scheduled_hook( 'wpadcf7sl_submission_count_reset' );
	}

	/**
	 * Required plugins validation.
	 *
	 * @return void
	 */
	public static function check_cf7_exist() {
		$required_install = false;

		if ( ! function_exists( 'is_plugin_active_for_network' ) || ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			$required_install = true;
		}

		if ( is_multisite() ) {
			if ( is_plugin_active_for_network( 'contact-form-7/wp-contact-form-7.php' ) ) {
				$required_install = true;
			}
		}

		return $required_install;
	}

	/**
	 * CF7 plugin activation notice.
	 *
	 * @return void
	 * */
	public function activation_notice() {
		$core_plugin_file = 'contact-form-7/wp-contact-form-7.php';

		include_once WPADCF7SL_DIR . 'templates/cf7-activation-notice.php';
	}

	/**
	 * Initialize the plugin tracker.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	public function init_appsero_tracker() {
		if ( ! class_exists( 'Appsero\Client' ) ) {
			require_once __DIR__ . '/appsero/src/Client.php';
		}

		$client = new Appsero\Client( '3b22a12f-448f-49f4-91ad-4e4f90f309bf', 'CF7 Form Submission Limit &#8211; WPAppsDev', __FILE__ );

		// Active insights
		$client->insights()->init();
	}
}

/**
 * Initializes the main plugin.
 *
 * @return \WPAppsDev_CF7_Limit
 */
function wpadcf7sl_process() {
	return WPAppsDev_CF7_Limit::init();
}

// kick-off the plugin
wpadcf7sl_process();
