<?php



add_action('admin_menu', function(){
    add_options_page(
        'EasyBroker Sync Optons',
        'EasyBroker Sync Optons',
        'manage_options',
        'easybroker-sync-options',
        'display_ebs_options'
    );
});

function display_ebs_options(){
    echo '<div class="wrap">
	<h1>EasyBroker Sync Settings</h1>
	<form method="post" action="options.php">';
			
		settings_fields( 'ebs_settings' ); // settings group name
		do_settings_sections( 'ebs-slug' ); // just a page slug
		submit_button();

	echo '</form></div>';

}

add_action('admin_init', function (){
    register_setting(
        'ebs_settings',
        'easy_broker_api_key',
        'sanitize_text_field'
    );

    add_settings_section(
        'some_settings_section_id',
        '',
        '',
        'ebs-slug'
    );

    add_settings_field(
        'easy_broker_api_key',
        'EasyBroker API Key',
        'ebs_text_field_html',
        'ebs-slug',
        'some_settings_section_id',
        array(
            'label_for' => 'easy_broker_api_key',
            'class' => 'ebs-class'
        )
    );

});

function ebs_text_field_html(){
    $text = get_option('easy_broker_api_key');
    printf(
        '<input type="password" id="easy_broker_api_key" name="easy_broker_api_key" value="%s" />',
		esc_attr( $text )
    );
}