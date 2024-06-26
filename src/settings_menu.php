<?php



add_action('admin_menu', function () {
    add_options_page(
        'EasyBroker Sync Optons',
        'EasyBroker Sync Optons',
        'manage_options',
        'easybroker-sync-options',
        'display_ebs_options'
    );
});

function display_ebs_options()
{
    echo '<div class="wrap">
	<h1>EasyBroker Sync Settings</h1>
	<form method="post" action="options.php">';

    settings_fields('ebs_settings'); // settings group name
    do_settings_sections('ebs-plugin-options'); // just a page slug
    submit_button();

    echo '</form></div>';
}

add_action('admin_init', function () {

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

    add_settings_field(
        'easybroker_sync_negative_filter',
        'Negative Filter',
        'ebs_negative_filter_field_html',
        'ebs-plugin-options',
        'some_settings_section_id',
        array(
            'label_for' => 'easy_broker_negative_filter',
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

    register_setting(
        'ebs_settings',
        'easybroker_sync_negative_filter'
    );
});

function ebs_api_field_html()
{
    $text = get_option('easybroker_sync_api_key');
    echo '<input type="password" id="easybroker_sync_api_key" name="easybroker_sync_api_key" value="' . esc_attr($text) . '" /><p><a href="https://dev.easybroker.com/docs/autenticaci%C3%B3n">Obtener una API Key de EasyBroker</a></p>';
}

function ebs_author_field_html()
{
    $text = get_option('easybroker_sync_author_id');
    echo '<input type="number" id="easybroker_sync_author_id" name="easybroker_sync_author_id" value="' . esc_attr($text) . '" /><p>El id del usuario de wordpress que será el autor de las propiedades importadas</p>';
}

function ebs_tag_filter_field_html()
{
    $text = get_option('easybroker_sync_tag_filter');
    echo '<input type="text" id="easybroker_sync_tag_filter" name="easybroker_sync_tag_filter" value="' . esc_attr($text) . '" /><p>Puedes filtrar las propiedades por tags de easybroker</p>';
}

function ebs_negative_filter_field_html()
{
    $value = get_option('easybroker_sync_negative_filter');

    echo '<input type="checkbox" id="easybroker_sync_negative_filter" name="easybroker_sync_negative_filter" value="1"' . checked(1, $value, false) . '><p>Hacer negativo el Filter by Tag</p>';
}
