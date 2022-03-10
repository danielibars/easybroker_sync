<?php

/**
 * Checks if the Directorist plugin is activated
 *
 * If the Directorist plugin is not active, then don't allow the
 * activation of this plugin.
 *
 * @since 1.0.0
 */
function ebs_activate() {
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
      include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
    if ( current_user_can( 'activate_plugins' ) && ! class_exists( 'directorist' ) ) {
      // Deactivate the plugin.
      deactivate_plugins( plugin_basename( __FILE__ ) );
      // Throw an error in the WordPress admin console.
      $error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'This plugin requires ', 'simplewlv' ) . '<a href="' . esc_url( 'https://wordpress.org/plugins/simplewlv/' ) . '">Directorist</a>' . esc_html__( ' plugin to be active.', 'simplewlv' ) . '</p>';
      die( $error_message ); // WPCS: XSS ok.
    }
  }
  register_activation_hook( __FILE__, 'simplewlv_activate' );