<input type="hidden" name="wpadcf7sl_remaining" value="<?php echo esc_attr( $remaining ); ?>">
<input type="hidden" name="wpadcf7sl_after_submission" value="<?php echo esc_attr( $after_submission ); ?>">
<input type="hidden" name="wpadcf7sl_reload_delay" value="<?php echo esc_attr( $reload_delay ); ?>">
<input type="hidden" name="wpadcf7sl_redirect_page" value="<?php echo esc_attr( $redirect_page ); ?>">
<span class="wpcf7-form-control-wrap <?php echo esc_attr( $tag->name ); ?>">
	<?php if ( $remaining > 0 ) { ?>
		<?php if ( $is_message_disable != 1 ) { ?>
			<?php echo sprintf( '%s %s %s.', __( 'This form can be submitted up to', 'wpappsdev-submission-limit-cf7' ), esc_attr( $remaining ), __( 'times', 'wpappsdev-submission-limit-cf7' ) ); ?>
		<?php } ?>
	<?php } else { ?>
		<?php echo sprintf( __( 'You cannot submit this form. Because form submission limit over.', 'wpappsdev-submission-limit-cf7' ) ); ?>
	<?php } ?>
</span>
