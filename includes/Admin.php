<?php

namespace WPAppsDev\CF7SL;

use WPCF7_ContactForm;

/**
 * The admin class.
 */
class Admin {
	/**
	 * Initialize the class.
	 */
	public function __construct() {
		add_action( 'wpcf7_editor_panels', [ $this, 'cf7_custom_settings' ] );
		add_action( 'wpcf7_save_contact_form', [ $this, 'cf7_save_settings' ] );
	}

	/**
	 * Add settings panel for contact form 7 form settings.
	 *
	 * @since  1.0.0
	 *
	 * @param array $panels
	 *
	 * @return array
	 */
	public function cf7_custom_settings( $panels ) {
		$panels['cf7_submission_limit'] = [
			'title'    => __( 'Submission Limit', 'wpappsdev-submission-limit-cf7' ),
			'callback' => [ $this, 'cf7_settings_fields' ],
		];

		return $panels;
	}

	/**
	 * Add settings fields in new settings panel.
	 *
	 * @since  1.0.0
	 *
	 * @param array $panels
	 *
	 * @return void
	 */
	public function cf7_settings_fields() {
		$cf7                = WPCF7_ContactForm::get_current();
		$cf7_id             = $cf7->id();
		$is_enabled         = get_post_meta( $cf7_id, 'wpadcf7sl-limit-enabled', true );
		$reset_date         = get_post_meta( $cf7_id, 'wpadcf7sl-reset-date', true );
		$total_submission   = get_post_meta( $cf7_id, 'wpadcf7sl-total-submission', true );
		$period_interval    = get_post_meta( $cf7_id, 'wpadcf7sl-period-interval', true );
		$period             = get_post_meta( $cf7_id, 'wpadcf7sl-period', true );
		$limit_type         = get_post_meta( $cf7_id, 'wpadcf7sl-limit-type', true );
		$is_reset_disable   = get_post_meta( $cf7_id, 'wpadcf7sl-reset-limit-disable', true );
		$after_submission   = get_post_meta( $cf7_id, 'wpadcf7sl-after-submission', true );
		$reload_delay       = get_post_meta( $cf7_id, 'wpadcf7sl-page-reload-delay', true );
		$redirect_page      = get_post_meta( $cf7_id, 'wpadcf7sl-redirect-page', true );
		$is_message_disable = get_post_meta( $cf7_id, 'wpadcf7sl-disable-display-message', true );

		if ( $is_enabled && 1 == $is_enabled ) {
			$checked = 'checked';
		} else {
			$checked = '';
		}

		if ( $is_reset_disable && 1 == $is_reset_disable ) {
			$disable_checked = 'checked';
		} else {
			$disable_checked = '';
		}

		if ( $is_message_disable && 1 == $is_message_disable ) {
			$message_disable = 'checked';
		} else {
			$message_disable = '';
		}

		$interval_options = [
			'1' => __( 'Every', 'wpappsdev-submission-limit-cf7' ),
			'2' => __( 'Every 2nd', 'wpappsdev-submission-limit-cf7' ),
			'3' => __( 'Every 3rd', 'wpappsdev-submission-limit-cf7' ),
			'4' => __( 'Every 4th', 'wpappsdev-submission-limit-cf7' ),
			'5' => __( 'Every 5th', 'wpappsdev-submission-limit-cf7' ),
			'6' => __( 'Every 6th', 'wpappsdev-submission-limit-cf7' ),
		];

		$period_options = [
			'day'   => __( 'Day', 'wpappsdev-submission-limit-cf7' ),
			'week'  => __( 'Week', 'wpappsdev-submission-limit-cf7' ),
			'month' => __( 'Month', 'wpappsdev-submission-limit-cf7' ),
			'year'  => __( 'Year', 'wpappsdev-submission-limit-cf7' ),
		];

		$type_options = [
			'formsubmit'     => __( 'Depend on total form submit', 'wpappsdev-submission-limit-cf7' ),
			'userformsubmit' => __( 'Depend on user total form submit', 'wpappsdev-submission-limit-cf7' ),
		];

		$submission_options = [
			''             => __( 'Do nothing', 'wpappsdev-submission-limit-cf7' ),
			'reloadpage'   => __( 'Reload Page', 'wpappsdev-submission-limit-cf7' ),
			'redirectpage' => __( 'Redirect specific page', 'wpappsdev-submission-limit-cf7' ),
		];

		$args = [
			'is_enabled'         => $is_enabled,
			'checked'            => $checked,
			'reset_date'         => $reset_date,
			'total_submission'   => $total_submission,
			'cf7_id'             => $cf7_id,
			'period_interval'    => $period_interval,
			'period'             => $period,
			'limit_type'         => $limit_type,
			'interval_options'   => $interval_options,
			'period_options'     => $period_options,
			'type_options'       => apply_filters( 'wpadcf7sl-limit-type-options', $type_options ),
			'is_reset_disable'   => $is_reset_disable,
			'disable_checked'    => $disable_checked,
			'submission_options' => $submission_options,
			'after_submission'   => $after_submission,
			'reload_delay'       => $reload_delay,
			'redirect_page'      => $redirect_page,
			'redirect_pages'     => self::get_pages_options(),
			'is_message_disable' => $is_message_disable,
			'message_disable'    => $message_disable,
		];

		wpadcf7sl_get_template( 'templates/custom-settings.php', $args );
	}

	/**
	 * Save custom form settings.
	 *
	 * @since  1.0.0
	 *
	 * @param object $cf7
	 *
	 * @return void
	 */
	public function cf7_save_settings( $cf7 ) {
		$cf7_id = $cf7->id();
		$posted = wp_unslash( $_POST );
		$nonce  = sanitize_text_field( $posted['wpadcf7sl_nonce'] );

		if ( ! wp_verify_nonce( $nonce, 'wpadcf7sl-settings' ) ) {
			wp_send_json_error( [
				'type'    => 'nonce',
				'message' => __( 'Are you cheating?', 'mplus-moreminutes' ),
			] );
		}

		if ( isset( $posted['wpadcf7sl-limit-enabled'] ) ) {
			update_post_meta( $cf7_id, 'wpadcf7sl-limit-enabled', 1 );
		} else {
			update_post_meta( $cf7_id, 'wpadcf7sl-limit-enabled', 0 );
		}

		if ( isset( $posted['wpadcf7sl-reset-limit-disable'] ) ) {
			update_post_meta( $cf7_id, 'wpadcf7sl-reset-limit-disable', 1 );
		} else {
			update_post_meta( $cf7_id, 'wpadcf7sl-reset-limit-disable', 0 );
			update_post_meta( $cf7_id, 'wpadcf7sl-reset-date', $posted['wpadcf7sl-reset-date'] );
			update_post_meta( $cf7_id, 'wpadcf7sl-period-interval', $posted['wpadcf7sl-period-interval'] );
			update_post_meta( $cf7_id, 'wpadcf7sl-period', $posted['wpadcf7sl-period'] );
		}

		if ( isset( $posted['wpadcf7sl-disable-display-message'] ) ) {
			update_post_meta( $cf7_id, 'wpadcf7sl-disable-display-message', 1 );
		} else {
			update_post_meta( $cf7_id, 'wpadcf7sl-disable-display-message', 0 );
		}

		update_post_meta( $cf7_id, 'wpadcf7sl-total-submission', $posted['wpadcf7sl-total-submission'] );
		update_post_meta( $cf7_id, 'wpadcf7sl-limit-type', $posted['wpadcf7sl-limit-type'] );
		update_post_meta( $cf7_id, 'wpadcf7sl-after-submission', $posted['wpadcf7sl-after-submission'] );
		update_post_meta( $cf7_id, 'wpadcf7sl-page-reload-delay', $posted['wpadcf7sl-page-reload-delay'] );
		update_post_meta( $cf7_id, 'wpadcf7sl-redirect-page', $posted['wpadcf7sl-redirect-page'] );

		do_action( 'wpadcf7sl-submission-limit-save', $cf7_id, $posted );
	}

	/**
	 * Get page select options.
	 *
	 * @since  2.3.0
	 *
	 * @return array
	 */
	public static function get_pages_options() {
		$pages   = get_pages();
		$options = [ -1 => __( 'Select Page', 'wpappsdev-submission-limit-cf7' ) ];

		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		return $options;
	}
}
