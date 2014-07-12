<?php

if ( ! class_exists('WeDevs_Settings_API' ) )
    require_once ( 'class-settings-api.php' );

/**
 * MasterSlider Setting page
 *
 * @author Tareq Hasan
 */
if ( !class_exists('MSP_Settings' ) ):

class MSP_Settings {

    private $settings_api;

    function __construct() {

        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
        add_action( 'admin_action_msp_envato_license', array( $this, 'envato_license_updated' ) );
        
        add_action( 'admin_footer-master-slider_page_masterslider-setting', array( $this, 'print_setting_script' ) );
        add_filter( 'axiom_wedev_setting_section_submit_button', array( $this, 'section_submit_button' ), 10, 2 );
    }


    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields  ( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }


    function section_submit_button( $button_markup, $section ){
        if( isset( $section['id'] ) && 'msp_envato_license' == $section['id'] ){
            $is_license_actived = get_option( MSWP_SLUG . '_is_license_actived', 0 );
            return sprintf( '<a id="validate_envato_license" class="button button-primary button-large" data-activate="%1$s" data-isactive="%3$d" data-deactivate="%2$s" data-validation="%4$s" >%1$s</a>%5$s', 
                            __( 'Activate License', MSWP_TEXT_DOMAIN ), __( 'Deactivate License', MSWP_TEXT_DOMAIN ), (int)$is_license_actived,
                            __( 'Validating ..', MSWP_TEXT_DOMAIN ), '<div class="msp-msg-nag">is not actived</div>' );
        }
        return $button_markup;
    }


    function admin_menu() {
        
        add_submenu_page(
            MSWP_SLUG,
            __( 'Settings' , MSWP_TEXT_DOMAIN ),
            __( 'Settings' , MSWP_TEXT_DOMAIN ),
            apply_filters( 'masterslider_setting_capability', 'manage_options' ),
            MSWP_SLUG . '-setting',
            array( $this, 'render_setting_page' )
        );
    }

    function get_settings_sections() {
        $sections = array(
            
            array(
                'id' => 'msp_general_setting',
                'title' => __( 'General Settings', MSWP_TEXT_DOMAIN )
            )
        );

        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        
        $settings_fields = array();
            
        $settings_fields['msp_general_setting'] = array(
            array(
                'name'  => 'hide_info_table',
                'label' => __( 'Hide info table', MSWP_TEXT_DOMAIN ),
                'desc'  => __( 'If you want to hide "Latest video tutorials" table on master slider admin panel check this field.', MSWP_TEXT_DOMAIN ),
                'type'  => 'checkbox'
            ),
            array(
                'name'  => '_enable_cache',
                'label' => __( 'Enable cache?', MSWP_TEXT_DOMAIN ),
                'desc'  => __( 'Enable cache to make Masterslider even more faster!', MSWP_TEXT_DOMAIN ),
                'type'  => 'checkbox'
            ),
            array(
                'name'  => '_cache_period',
                'label' => __( 'Cache period time', MSWP_TEXT_DOMAIN ),
                'desc'  => __( 'The cache refresh time in hours. Cache is also cleared when you click on "Save Changes" in slider panel.', MSWP_TEXT_DOMAIN ),
                'type'  => 'text',
                'default' => '12',
                'sanitize_callback' => 'floatval'
            )
        );

        return $settings_fields;
    }

    function render_setting_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }


    /**
     * This code uses localstorage for displaying active tabs
     * 
     */
    function print_setting_script() {
        ?>
        <script>
        (function($) {
        $(function() {  

        });
        })(jQuery);
        </script>

        <style>
            .master-slider_page_masterslider-setting .wrap input[disabled] { background-color:#e0e0e0; }
            .msp-msg-nag {
                display: inline-block;
                line-height: 14px;
                padding: 8px 15px;
                font-size: 14px;
                text-align: left;
                margin: 0 20px;
                background-color: #fff;
                border-left: 4px solid #ffba00;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            }
        </style>
        <?php
    }

}

endif;

$settings = new MSP_Settings();