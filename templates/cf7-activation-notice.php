<div class="updated" id="installer-notice" style="padding: 1em; position: relative;">
	<h2><?php _e( 'Your CF7 Form Submission Limit plugins is almost ready!', 'wpappsdev-submission-limit-cf7' ); ?></h2>
	<?php if ( file_exists( WP_PLUGIN_DIR . '/' . $core_plugin_file ) && is_plugin_inactive( 'contact-form-7/wp-contact-form-7.php' ) ) { ?>
		<p><?php echo sprintf( __( 'You just need to activate the <strong>%s</strong> to make it functional.', 'wpappsdev-submission-limit-cf7' ), 'Contact Form 7' ); ?></p>
		<p>
			<a class="button button-primary" href="<?php echo wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $core_plugin_file . '&amp;plugin_status=all&amp;paged=1&amp;s=', 'activate-plugin_' . $core_plugin_file ); ?>"  title="<?php _e( 'Activate this plugin', 'wpappsdev-submission-limit-cf7' ); ?>"><?php _e( 'Activate', 'wpappsdev-submission-limit-cf7' ); ?></a>
		</p>
	<?php } else { ?>
		<p><?php echo sprintf( __( 'You just need to install & active the %sContact Form 7%s to make it functional.', 'wpappsdev-submission-limit-cf7' ), '<a target="_blank" href="https://wordpress.org/plugins/contact-form-7/">', '</a>' ); ?></p>
	<?php } ?>
</div>
