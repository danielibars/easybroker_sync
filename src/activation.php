<?php

// Activación del Plugin
register_activation_hook( __FILE__, 'ibars_plugin_activation' );
function ibars_plugin_activation() {
    if( ! wp_next_scheduled( 'easybroker_sync_cron_hook' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'EasyBrokerSync', 'easybroker_sync_cron_hook' );
    }
}

// Desactivación del plugin
register_deactivation_hook( __FILE__, 'ibars_plugin_desativation' );
function ibars_plugin_desativation() {
    wp_clear_scheduled_hook( 'easybroker_sync_cron_hook' );
}