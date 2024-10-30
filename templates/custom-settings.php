<?php
if ( ! defined( 'WPINC' ) ) {
	exit;
}
wp_nonce_field( 'wpadcf7sl-settings', 'wpadcf7sl_nonce', false );
?>

<div class="wpadcf7sl-settings">
	<h2><?php _e( 'Submission Limit', 'wpappsdev-submission-limit-cf7' ); ?></h2>
	<fieldset>
		<legend><?php _e( 'If you enable form submission limit for this form please put this tag', 'wpappsdev-submission-limit-cf7' ); ?> <span class="mailtag code used"><b>[counter formid:<?php echo esc_attr( $cf7_id ); ?>]</b></span> <?php _e( 'in your form template. see', 'wpappsdev-submission-limit-cf7' ); ?> <a href="https://youtu.be/Tj7ChYRtajk"><?php _e( 'Configure Form', 'wpappsdev-submission-limit-cf7' ); ?></a>.</legend>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="wpadcf7sl-limit-enabled"><?php _e( 'Enable Submission Limit', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td><input id="wpadcf7sl-limit-enabled" type="checkbox" name="wpadcf7sl-limit-enabled" value="1" <?php echo esc_attr( $checked ); ?> ></td>
				</tr>
				<tr>
					<th scope="row"><label for="wpadcf7sl-limit-type"><?php _e( 'Submission Limit Type', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td>
						<select id="wpadcf7sl-limit-type" name="wpadcf7sl-limit-type">
							<?php foreach ( $type_options as $key => $value ) { ?>
								<?php $selected = ( $key == $limit_type ) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $value ); ?></option>
							<?php } ?>
						</select>
						<div class="wpadcf7sl-desc">
							<p class="wpadcf7sl-hidden" id="userformsubmit"><b><?php _e( 'Note', 'wpappsdev-submission-limit-cf7' ); ?> : </b><?php _e( 'User cannot submit form without login.', 'wpappsdev-submission-limit-cf7' ); ?></p>
							<?php do_action( 'wpadcf7sl_limit_type_note' ); ?>
						</div>
					</td>
				</tr>
				<?php do_action( 'wpadcf7sl_after_limit_type', $cf7_id ); ?>
				<tr class="wpadcf7sl-hidden wpadcf7sl-limit-type if-show-limit-type-formsubmit if-show-limit-type-userformsubmit" >
					<th scope="row"><label for="wpadcf7sl-total-submission"><?php _e( 'Total Submission', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td><input id="wpadcf7sl-total-submission" type="text" name="wpadcf7sl-total-submission" value="<?php echo esc_attr( $total_submission ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="wpadcf7sl-disable-display-message"><?php _e( 'Disable Displaying Remaining Message', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td><input id="wpadcf7sl-disable-display-message" type="checkbox" name="wpadcf7sl-disable-display-message" value="1" <?php echo esc_attr( $message_disable ); ?> ></td>
				</tr>
				<tr>
					<th scope="row"><label for="wpadcf7sl-after-submission"><?php _e( 'After Successfully Form Submission', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td>
						<select id="wpadcf7sl-after-submission" name="wpadcf7sl-after-submission">
							<?php foreach ( $submission_options as $key => $value ) { ?>
								<?php $selected = ( $key == $after_submission ) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $value ); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr class="wpadcf7sl-hidden wpadcf7sl-after-submission" id="reloadpage">
					<th scope="row"><label for="wpadcf7sl-page-reload-delay"><?php _e( 'Page Reload Delay', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td>
						<input id="wpadcf7sl-page-reload-delay" type="text" name="wpadcf7sl-page-reload-delay" value="<?php echo esc_attr( $reload_delay ); ?>">
						<div class="wpadcf7sl-desc">
							<p><?php _e( 'Please input how many second delay for page reload.', 'wpappsdev-submission-limit-cf7' ); ?></p>
						</div>
					</td>
				</tr>
				<tr class="wpadcf7sl-hidden wpadcf7sl-after-submission" id="redirectpage">
					<th scope="row"><label for="wpadcf7sl-redirect-page"><?php _e( 'Redirect Page', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td>
						<select id="wpadcf7sl-redirect-page" name="wpadcf7sl-redirect-page">
							<?php foreach ( $redirect_pages as $key => $value ) { ?>
								<?php $selected = ( $key == $redirect_page ) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $value ); ?></option>
							<?php } ?>
						</select>
						<div class="wpadcf7sl-desc">
							<p><?php _e( 'Please select which page you want to redirect.', 'wpappsdev-submission-limit-cf7' ); ?></p>
						</div>
					</td>
				</tr>
				<?php do_action( 'wpadcf7sl_before_reset_limit_settings', $cf7_id ); ?>
				<tr>
					<th scope="row"><label for="wpadcf7sl-reset-limit-disable"><?php _e( 'Disable Reset Submission Limit', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td><input id="wpadcf7sl-reset-limit-disable" type="checkbox" name="wpadcf7sl-reset-limit-disable" value="1" <?php echo esc_attr( $disable_checked ); ?> ></td>
				</tr>
				<tr class="wpadcf7sl-reset-limit if-show-reset-limit-enable">
					<th scope="row" colspan="2"><?php _e( 'Reset Submission Limit', 'wpappsdev-submission-limit-cf7' ); ?></th>
				</tr>
				<tr class="if-show-reset-limit-enable">
					<th scope="row"><label for="wpadcf7sl-reset-date"><?php _e( 'Start Date', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td><input id="wpadcf7sl-reset-date" type="date" name="wpadcf7sl-reset-date" value="<?php echo esc_attr( $reset_date ); ?>"></td>
				</tr>
				<tr class="if-show-reset-limit-enable">
					<th scope="row"><label for="wpadcf7sl-reset-submission-limit"><?php _e( 'Reset Interval', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td>
						<select id="wpadcf7sl-period-interval" name="wpadcf7sl-period-interval">
							<?php foreach ( $interval_options as $key => $value ) { ?>
								<?php $selected = ( $key == $period_interval ) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $value ); ?></option>
							<?php } ?>
						</select>
						<select id="wpadcf7sl-period" name="wpadcf7sl-period">
							<?php foreach ( $period_options as $key => $value ) { ?>
								<?php $selected = ( $key == $period ) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_attr( $value ); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr class="if-show-reset-limit-enable wpadcf7sl-instant-reset-tr">
					<th scope="row"><label for="wpadcf7sl-instant-reset"><?php _e( 'Instant Reset', 'wpappsdev-submission-limit-cf7' ); ?></label></th>
					<td><input id="wpadcf7sl-instant-reset" type="button" name="wpadcf7sl-instant-reset" value="<?php _e( 'Reset Limit', 'wpappsdev-submission-limit-cf7' ); ?>" data-formid="<?php echo esc_attr( $cf7_id ); ?>"></td>
				</tr>
				<?php do_action( 'wpadcf7sl_settings_fields_end', $cf7_id ); ?>
			</tbody>
		</table>
	</fieldset>
</div>