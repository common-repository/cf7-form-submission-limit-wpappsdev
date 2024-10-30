<?php

namespace WPAppsDev\CF7SL;

/**
 * Ajax handler for this plugins.
 */
class Ajax {
	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		// Submission limit reset process.
		add_action( 'wp_ajax_reset_submission_limit', [ $this, 'reset_submission_limit_process' ] );
	}

	/**
	 * Submission limit reset process.
	 *
	 * @return void
	 */
	public function reset_submission_limit_process() {
		$post_data = wp_unslash( $_POST );
		$nonce     = isset( $post_data['_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ) : '';

		// Nonce protection.
		if ( ! wp_verify_nonce( $nonce, 'admin_security' ) ) {
			wp_send_json_error(
				[
					'type'    => 'nonce',
					'message' => __( 'Are you cheating?', 'wpappsdev-donation-manager' ),
				]
			);

			wp_die();
		}

		$form_id    = $post_data['formId'];
		$limit_type = $post_data['limitType'];

		reset_submission_limit( $form_id, $limit_type );

		wp_send_json_success();
		wp_die();
	}
}
