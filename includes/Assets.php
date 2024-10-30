<?php

namespace WPAppsDev\CF7SL;

class Assets {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_all_scripts' ], 10 );

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
		} else {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front_scripts' ] );
		}
	}

	/**
	 * Register all scripts and styles.
	 */
	public function register_all_scripts() {
		$styles  = $this->get_styles();
		$scripts = $this->get_scripts();

		$this->register_styles( $styles );
		$this->register_scripts( $scripts );

		do_action( 'wpadcf7sl_register_scripts' );
	}

	/**
	 * Get registered styles.
	 *
	 * @return array
	 */
	public function get_styles() {
		$prefix         = self::get_prefix();

		// All CSS file list.
		$styles = [
			'wpadcf7sl-admin' => [
				'src'     => WPADCF7SL_ASSETS . 'css/wpadcf7sl-admin.css',
				'deps'    => [],
				'version' => filemtime( WPADCF7SL_DIR . '/assets/css/wpadcf7sl-admin.css' ),
			],
			'wpadcf7sl-public' => [
				'src'     => WPADCF7SL_ASSETS . 'css/wpadcf7sl-public.css',
				'deps'    => [],
				'version' => filemtime( WPADCF7SL_DIR . '/assets/css/wpadcf7sl-public.css' ),
			],
			'waitMe.min' => [
				'src'     => WPADCF7SL_ASSETS . 'css/waitMe.min.css',
				'deps'    => [],
				'version' => filemtime( WPADCF7SL_DIR . '/assets/css/waitMe.min.css' ),
			],
		];

		return $styles;
	}

	/**
	 * Get all registered scripts.
	 *
	 * @return array
	 */
	public function get_scripts() {
		$prefix         = self::get_prefix();

		// All JS file list.
		$scripts = [
			// Register scripts
			'wpadcf7sl-admin' => [
				'src'     => WPADCF7SL_ASSETS . 'js/wpadcf7sl-admin.js',
				'deps'    => ['jquery'],
				'version' => filemtime( WPADCF7SL_DIR . 'assets/js/wpadcf7sl-admin.js' ),
			],
			'wpadcf7sl-public' => [
				'src'     => WPADCF7SL_ASSETS . 'js/wpadcf7sl-public.js',
				'deps'    => ['jquery'],
				'version' => filemtime( WPADCF7SL_DIR . 'assets/js/wpadcf7sl-public.js' ),
			],
			'waitMe.min' => [
				'src'     => WPADCF7SL_ASSETS . 'js/waitMe.min.js',
				'deps'    => ['jquery'],
				'version' => filemtime( WPADCF7SL_DIR . 'assets/js/waitMe.min.js' ),
			],
		];

		return $scripts;
	}

	/**
	 * Get file prefix.
	 *
	 * @return string
	 */
	public static function get_prefix() {
		$prefix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		return $prefix;
	}

	/**
	 * Register scripts.
	 *
	 * @param array $scripts
	 *
	 * @return void
	 */
	public function register_scripts( $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			$deps      = isset( $script['deps'] ) ? $script['deps'] : false;
			$in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : true;
			$version   = isset( $script['version'] ) ? $script['version'] : WPADCF7SL_VERSION;

			wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
		}
	}

	/**
	 * Register styles.
	 *
	 * @param array $styles
	 *
	 * @return void
	 */
	public function register_styles( $styles ) {
		foreach ( $styles as $handle => $style ) {
			$deps    = isset( $style['deps'] ) ? $style['deps'] : false;
			$version = isset( $style['version'] ) ? $style['version'] : WPADCF7SL_VERSION;

			wp_register_style( $handle, $style['src'], $deps, $version );
		}
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_admin_scripts( $hook ) {
		$default_script = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'admin_security' ),
			'success' => __( 'Form submission limit reset successfully.', 'wpappsdev-submission-limit-cf7' ),
			'error'   => __( 'Form submission limit reset not successfully.', 'wpappsdev-submission-limit-cf7' ),
		];

		$localize_data = apply_filters( 'wpadcf7sl_admin_localized_args', $default_script );

		if ( self::is_cf7_page() ) {
			// Enqueue scripts
			wp_enqueue_script( 'wpadcf7sl-admin' );
			wp_localize_script( 'wpadcf7sl-admin', 'wpadcf7sl_admin', $localize_data );

			// Enqueue Styles
			wp_enqueue_style( 'wpadcf7sl-admin' );
			wp_enqueue_style( 'waitMe.min' );
			wp_enqueue_script( 'waitMe.min' );
		}

		do_action( 'wpadcf7sl_enqueue_admin_scripts' );
	}

	/**
	 * Enqueue front-end scripts.
	 */
	public function enqueue_front_scripts() {
		$default_script = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'public_security' ),
		];

		// Front end localize data
		$localize_data = apply_filters( 'wpadcf7sl_localized_args', $default_script );
		wp_localize_script( 'jquery', 'wpadcf7sl_public', $localize_data );

		// Enqueue scripts
		wp_enqueue_script( 'wpadcf7sl-public' );

		// Enqueue Styles
		//wp_enqueue_style( 'wpadcf7sl-public' );

		do_action( 'wpadcf7sl_enqueue_scripts' );
	}

	/**
	 * Checked current page is cf7 edit page.
	 *
	 * @return bool
	 */
	public static function is_cf7_page() {
		if ( isset( $_GET['page'] ) && 'wpcf7' == $_GET['page'] ) {
			return true;
		} else {
			return false;
		}
	}
}
