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
		do_settings_sections( 'ebs-plugin-options' ); // just a page slug
		submit_button();

	echo '</form></div>';

}

add_action('admin_init', function (){
    
    add_settings_section(
        'some_settings_section_id',
        '',
        '',
        'ebs-plugin-options'
    );

    add_settings_field(
        'easybroker_sync_api_key',
        'EasyBroker API Key',
        'ebs_api_field_html',
        'ebs-plugin-options',
        'some_settings_section_id',
        array(
            'label_for' => 'easy_broker_api_key',
            'class' => 'ebs-class'
        )
    );

    add_settings_field(
        'easybroker_sync_author_id',
        'Author ID',
        'ebs_author_field_html',
        'ebs-plugin-options',
        'some_settings_section_id',
        array(
            'label_for' => 'easy_broker_author_id',
            'class' => 'ebs-class'
        )
    );

    add_settings_field(
        'easybroker_sync_tag_filter',
        'Filter by tag',
        'ebs_tag_filter_field_html',
        'ebs-plugin-options',
        'some_settings_section_id',
        array(
            'label_for' => 'easy_broker_tag_filter',
            'class' => 'ebs-class'
        )
    );

    register_setting(
        'ebs_settings',
        'easybroker_sync_api_key'
    );

    register_setting(
        'ebs_settings',
        'easybroker_sync_author_id'
    );

    register_setting(
        'ebs_settings',
        'easybroker_sync_tag_filter'
    );


});

function ebs_api_field_html(){
    $text = get_option('easybroker_sync_api_key');
    printf(
        '<input type="password" id="easybroker_sync_api_key" name="easybroker_sync_api_key" value="%s" />',
		esc_attr( $text )
    );
}

function ebs_author_field_html(){
    $text = get_option('easybroker_sync_author_id');
    printf(
        '<input type="number" id="easybroker_sync_author_id" name="easybroker_sync_author_id" value="%s" />',
		esc_attr( $text )
    );
}

function ebs_tag_filter_field_html(){
    $text = get_option('easybroker_sync_tag_filter');
    printf(
        '<input type="text" id="easybroker_sync_tag_filter" name="easybroker_sync_tag_filter" value="%s" />',
		esc_attr( $text )
    );
}