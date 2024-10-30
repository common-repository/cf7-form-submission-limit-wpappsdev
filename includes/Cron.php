<?php

namespace WPAppsDev\CF7SL;

/**
 * Cron handler for this plugins.
 */
class Cron {
	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wpadcf7sl_submission_count_reset', [ $this, 'process_submission_count_reset' ], 1 );
	}

	/**
	 * Submission count reset process.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function process_submission_count_reset() {
		$form_ids = self::get_all_enabled_cf7();

		foreach ( $form_ids as $form_id ) {
			$limit_type      = get_post_meta( $form_id, 'wpadcf7sl-limit-type', true );
			$disable_reset   = (int) get_post_meta( $form_id, 'wpadcf7sl-reset-limit-disable', true );
			$current_date    = gmdate( 'Y-m-d' );
			$reset_date      = get_post_meta( $form_id, 'wpadcf7sl-reset-date', true );
			$period_interval = get_post_meta( $form_id, 'wpadcf7sl-period-interval', true );
			$period          = get_post_meta( $form_id, 'wpadcf7sl-period', true );

			if ( strtotime( $reset_date ) > strtotime( $current_date ) ) {
				continue;
			}

			// Check reset limit disable or enable.
			if ( 1 === $disable_reset ) {
				continue;
			}

			reset_submission_limit( $form_id, $limit_type );

			// Update next reset date.
			$update_reset = date( 'Y-m-d', strtotime( "+{$period_interval} {$period}", strtotime( $reset_date ) ) );
			update_post_meta( $form_id, 'wpadcf7sl-reset-date', $update_reset );
		}
	}

	/**
	 * Get all cf7 form ids which is enabled for submission limit.
	 *
	 * @return array
	 */
	public static function get_all_enabled_cf7() {
		global $wpdb;

		$result = $wpdb->get_results(
			"SELECT post_id FROM {$wpdb->prefix}postmeta
			WHERE meta_key = 'wpadcf7sl-limit-enabled' AND meta_value = 1",
			ARRAY_A
		);

		$form_ids = wp_list_pluck( $result, 'post_id' );

		return $form_ids;
	}
}
