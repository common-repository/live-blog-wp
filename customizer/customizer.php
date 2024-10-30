<?php

namespace Live_Blog_WP\Customizer;

if ( ! defined( 'ABSPATH' ) ) exit;

class Customizer {

    public static function register( $wp_customize ) {

        /*
        * PANEL: Main
        */

        $wp_customize->add_panel( 'lbwp_panel_main', array(
            'title' => __( 'Live Blog WP', 'lbwp' ),
            'description' => __( 'Settings and options related to Live Blog WP.', 'lbwp'),
            'capability'    => 'edit_theme_options',
            'priority' => 9999,
        ) );

        /*
        * SECTION: functionality
        */

        $wp_customize->add_section( 'lbwp_section_functionality', array(
            'title' => __( 'Internal Functionality','lbwp' ),
            'description' => __( 'Adjust how LIve Blog WP operates.', 'lbwp' ),
            'panel' => 'lbwp_panel_main',
            'priority' => 1,
        ));

        $wp_customize->add_setting( 'lbwp_options[single_template]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'full_width',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[single_template]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Single Template', 'lbwp' ),
            'description' => __( 'The template used for layout on post singles.', 'lbwp' ),
            'choices' => array(
                'full_width' => __( 'Full Width', 'lbwp' ),
                'sidebar' => __( 'Sidebar' . \Live_Blog_WP\Instance::instance()->pro_tag(), 'lbwp' )
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[post_template]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'card_1',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[post_template]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Post Template', 'lbwp' ),
            'description' => __( 'These are the layouts for individual posts within a single.', 'lbwp' ),
            'choices' => array(
                'card_1' => __( 'Card 1', 'lbwp' ),
                'card_2' => __( 'Card 2', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[get_posts_interval]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '60',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[get_posts_interval]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Get Posts Interval (seconds)', 'lbwp' ),
            'description' => __( 'How long in seconds should we wait to check for new posts.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[step_offset]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '65',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[step_offset]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Step Offset', 'lbwp' ),
            'description' => __( 'The offset when stepping between posts.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_suffixAgo]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'ago',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_suffixAgo]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Ago Text', 'lbwp' ),
            'description' => __( 'The text to show how long ago a post was made.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_seconds]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'less than a minute',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_seconds]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Seconds Text', 'lbwp' ),
            'description' => __( 'The text when a post is less than a minute old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_minute]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'about a minute',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_minute]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Minute Text', 'lbwp' ),
            'description' => __( 'The text when a post is a few minutes old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_minutes]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '%d minutes',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_minutes]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Minutes Text', 'lbwp' ),
            'description' => __( 'The text when a post is more than a few minutes old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_hour]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'about an hour',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_hour]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Hour Text', 'lbwp' ),
            'description' => __( 'The text when a post is about an hour old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_hours]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'about %d hours',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_hours]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Hours Text', 'lbwp' ),
            'description' => __( 'The text when a post is a few hours old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_day]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'about a day',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_day]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Day Text', 'lbwp' ),
            'description' => __( 'The text when a post is a about a day old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_days]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '%d days',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_days]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Days Text', 'lbwp' ),
            'description' => __( 'The text when a post is a few days old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_month]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'about a month',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_month]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Month Text', 'lbwp' ),
            'description' => __( 'The text when a post is about a month old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_months]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '%d months',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_months]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Months Text', 'lbwp' ),
            'description' => __( 'The text when a post is a few months old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_year]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'about a year',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_year]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Year Text', 'lbwp' ),
            'description' => __( 'The text when a post is a few months old.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[label_js_years]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '%d years',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[label_js_years]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_functionality',
            'label' => __( 'Years Text', 'lbwp' ),
            'description' => __( 'The text when a post is a few months old.', 'lbwp' ),
        ));

        /*
        * SECTION: Spinner
        */

        $wp_customize->add_section( 'lbwp_section_spinner', array(
            'title' => __( 'Spinner','lbwp' ),
            'description' => __( 'Adjust how Live Blog WP spinner operates.', 'lbwp' ),
            'panel' => 'lbwp_panel_main',
            'priority' => 1,
        ));

        $wp_customize->add_setting( 'lbwp_options[spinner_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#1e87f0',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[spinner_color]', array(
                'label' => __( 'Color', 'lbwp' ),
                'section' => 'lbwp_section_spinner',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[spinner_size]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '2',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_float',
        ));

        $wp_customize->add_control( 'lbwp_options[spinner_size]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_spinner',
            'label' => __( 'Size', 'lbwp' ),
        ));

        /*
        * SECTION: Toolbar
        */

        $wp_customize->add_section( 'lbwp_section_toolbar', array(
            'title' => __( 'Toolbar' . \Live_Blog_WP\Instance::instance()->pro_tag(),'lbwp' ),
            'description' => __( 'Adjust how Live Blog WP toolbar operates.', 'lbwp' ),
            'panel' => 'lbwp_panel_main',
            'priority' => 1,
        ));

        $wp_customize->add_setting( 'lbwp_options[show_toolbar]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[show_toolbar]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Show Toolbar', 'lbwp' ),
            'description' => __( 'Show the toolbar section in templates.', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[support_blog_page]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[support_blog_page]', array(
            'type' => 'dropdown-pages',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Support Blog Page', 'lbwp' ),
            'description' => __( 'Your sales, subscribe or other suuport this blog page.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[support_blog_text]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'SUPPORT',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[support_blog_text]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Support Blog Text', 'lbwp' ),
            'description' => __( 'The text used for links that target your support blog page.', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[toolbar_background_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#1e87f0',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[toolbar_background_color]', array(
                'label' => __( 'Background Color', 'lbwp' ),
                'section' => 'lbwp_section_toolbar',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[toolbar_icon_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[toolbar_icon_color]', array(
                'label' => __( 'Icon Color', 'lbwp' ),
                'section' => 'lbwp_section_toolbar',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[toolbar_icon_hover_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#e5e5e5',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[toolbar_icon_hover_color]', array(
                'label' => __( 'Icon Hover Color', 'lbwp' ),
                'section' => 'lbwp_section_toolbar',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[toolbar_icon_size]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1.2',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_float',
        ));

        $wp_customize->add_control( 'lbwp_options[toolbar_icon_size]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Icon Size', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[toolbar_border_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#1e87f0',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[toolbar_border_color]', array(
                'label' => __( 'Border Color', 'lbwp' ),
                'section' => 'lbwp_section_toolbar',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[toolbar_border_width]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[toolbar_border_width]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Border Width (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[toolbar_padding]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '15',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[toolbar_padding]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Padding (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[toolbar_box_shadow]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[toolbar_box_shadow]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Box Shadow', 'lbwp' ),
            'choices' => array(
                '0' => __( 'None', 'lbwp' ),
                'uk-box-shadow-small' => __( 'Small', 'lbwp' ),
                'uk-box-shadow-medium' => __( 'Medium', 'lbwp' ),
                'uk-box-shadow-large' => __( 'Large', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[toolbar_z_index]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '980',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[toolbar_z_index]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Z Index', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[toolbar_offset]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[toolbar_offset]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'Offset', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[toolbar_new_text]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'NEW POSTS',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[toolbar_new_text]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'New Text', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[toolbar_new_text_size]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '18',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[toolbar_new_text_size]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_toolbar',
            'label' => __( 'New Text Size (px)', 'lbwp' ),
        ));

        /*
        * SECTION: Key Points
        */

        $wp_customize->add_section( 'lbwp_section_points', array(
            'title' => __( 'Points' . \Live_Blog_WP\Instance::instance()->pro_tag(), 'lbwp' ),
            'description' => __( 'Adjust how Live Blog WP key points operate.', 'lbwp' ),
            'panel' => 'lbwp_panel_main',
            'priority' => 1,
        ));

        $wp_customize->add_setting( 'lbwp_options[show_points]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[show_points]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Show Key Points', 'lbwp' ),
            'description' => __( 'Show the key points section in templates.', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[show_points_heading]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[show_points_heading]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Show Key Points Heading', 'lbwp' ),
            'description' => __( 'Show the key points heading text.', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[show_points_time]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[show_points_time]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Show Time', 'lbwp' ),
            'description' => __( 'Show the time above each key point.', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[points_text]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'Key Points',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[points_text]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Key Points Text', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[points_heading_tag]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'h4',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[points_heading_tag]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Heading Tag', 'lbwp' ),
            'choices' => array(
                'div' => __( 'DIV', 'lbwp' ),
                'h1' => __( 'H1', 'lbwp' ),
                'h2' => __( 'H2', 'lbwp' ),
                'h3' => __( 'H3', 'lbwp' ),
                'h4' => __( 'H4','lbwp' ),
                'h5' => __( 'H5', 'lbwp' ),
                'h6' => __( 'H6', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[points_columns]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'uk-child-width-1-3@m',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[points_columns]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Full Width Columns', 'lbwp' ),
            'choices' => array(
                'uk-child-width-1-1@m' => __( '1', 'lbwp' ),
                'uk-child-width-1-2@m' => __( '2', 'lbwp' ),
                'uk-child-width-1-3@m' => __( '3', 'lbwp' ),
                'uk-child-width-1-4@m' => __( '4', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[points_time_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#000000',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[points_time_color]', array(
                'label' => __( 'Time Color', 'lbwp' ),
                'section' => 'lbwp_section_points',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[points_link_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#1e87f0',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[points_link_color]', array(
                'label' => __( 'Link Color', 'lbwp' ),
                'section' => 'lbwp_section_points',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[points_link_hover_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#1e87f0',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[points_link_hover_color]', array(
                'label' => __( 'Link Hover Color', 'lbwp' ),
                'section' => 'lbwp_section_points',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[points_link_tag]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'h5',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[points_link_tag]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Link Tag', 'lbwp' ),
            'choices' => array(
                'div' => __( 'DIV', 'lbwp' ),
                'p' => __( 'P', 'lbwp' ),
                'h1' => __( 'H1', 'lbwp' ),
                'h2' => __( 'H2', 'lbwp' ),
                'h3' => __( 'H3', 'lbwp' ),
                'h4' => __( 'H4','lbwp' ),
                'h5' => __( 'H5', 'lbwp' ),
                'h6' => __( 'H6', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[points_margin_bottom]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '5',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[points_margin_bottom]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Point Margin Bottom (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[points_wrap_margin_bottom]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '15',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[points_wrap_margin_bottom]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_points',
            'label' => __( 'Wrap Margin Bottom (px)', 'lbwp' ),
        ));

        /*
        * SECTION: Alert
        */

        $wp_customize->add_section( 'lbwp_section_alert', array(
            'title' => __( 'Alert', 'lbwp' ),
            'description' => __( 'Adjust how Live Blog WP key points operate.', 'lbwp' ),
            'panel' => 'lbwp_panel_main',
            'priority' => 1,
        ));

        $wp_customize->add_setting( 'lbwp_options[show_alert]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[show_alert]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Show Alert', 'lbwp' ),
            'description' => __( 'Show the new posts alert on the page.', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[force_show_alert]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[force_show_alert]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Force Show Alert', 'lbwp' ),
            'description' => __( 'Force the alert to show to make styling easier.', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[alert_text]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'NEW POSTS',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[alert_text]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Alert Text', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[alert_tag]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'div',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[alert_tag]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Text Tag', 'lbwp' ),
            'choices' => array(
                'div' => __( 'DIV', 'lbwp' ),
                'p' => __( 'P', 'lbwp' ),
                'h1' => __( 'H1', 'lbwp' ),
                'h2' => __( 'H2', 'lbwp' ),
                'h3' => __( 'H3', 'lbwp' ),
                'h4' => __( 'H4','lbwp' ),
                'h5' => __( 'H5', 'lbwp' ),
                'h6' => __( 'H6', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[alert_background_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[alert_background_color]', array(
                'label' => __( 'Background Color', 'lbwp' ),
                'section' => 'lbwp_section_alert',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[alert_text_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#000000',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[alert_text_color]', array(
                'label' => __( 'Text Color', 'lbwp' ),
                'section' => 'lbwp_section_alert',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[alert_text_hover_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#000000',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[alert_text_hover_color]', array(
                'label' => __( 'Text Hover Color', 'lbwp' ),
                'section' => 'lbwp_section_alert',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[alert_border_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#000000',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[alert_border_color]', array(
                'label' => __( 'Border Color', 'lbwp' ),
                'section' => 'lbwp_section_alert',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[alert_border_width]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[alert_border_width]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Border Width (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[alert_top_bottom_padding]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '10',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[alert_top_bottom_padding]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Top / Bottom Padding (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[alert_left_right_padding]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '20',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[alert_left_right_padding]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Left / Right Padding (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[alert_box_shadow]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[alert_box_shadow]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Box Shadow', 'lbwp' ),
            'choices' => array(
                '0' => __( 'None', 'lbwp' ),
                'uk-box-shadow-small' => __( 'Small', 'lbwp' ),
                'uk-box-shadow-medium' => __( 'Medium', 'lbwp' ),
                'uk-box-shadow-large' => __( 'Large', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[alert_position]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'uk-position-bottom-right',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[alert_position]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_alert',
            'label' => __( 'Position', 'lbwp' ),
            'choices' => array(
                'uk-position-bottom-left' => __( 'Bottom Left', 'lbwp' ),
                'uk-position-bottom-right' => __( 'Bottom Right', 'lbwp' ),
                'uk-position-bottom-center' => __( 'Bottom Center', 'lbwp' ),
                'uk-position-top-left' => __( 'Top Left', 'lbwp' ),
                'uk-position-top-right' => __( 'Top Right', 'lbwp' ),
                'uk-position-top-center' => __( 'Top Center', 'lbwp' ),
            )
        ) );

        /*
        * SECTION: Tab
        */

        $wp_customize->add_section( 'lbwp_section_tab', array(
            'title' => __( 'Tab' . \Live_Blog_WP\Instance::instance()->pro_tag(), 'lbwp' ),
            'description' => __( 'Adjust how Live Blog tab operates.', 'lbwp' ),
            'panel' => 'lbwp_panel_main',
            'priority' => 1,
        ));

        $wp_customize->add_setting( 'lbwp_options[tab_text]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '(NEW POSTS)',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[tab_text]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_tab',
            'label' => __( 'Tab Text', 'lbwp' ),
        ));

        /*
        * SECTION: Pinned Post
        */

        $wp_customize->add_section( 'lbwp_section_pinned', array(
            'title' => __( 'Pinned', 'lbwp' ),
            'description' => __( 'Adjust how Live Blog pinned post operates.', 'lbwp' ),
            'panel' => 'lbwp_panel_main',
            'priority' => 1,
        ));

        $wp_customize->add_setting( 'lbwp_options[show_pinned]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[show_pinned]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_pinned',
            'label' => __( 'Show Pinned Post', 'lbwp' ),
            'description' => __( 'Show the pinned post at the top of the feed.', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[pinned_margin_bottom]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '20',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[pinned_margin_bottom]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_pinned',
            'label' => __( 'Margin Bottom (px)', 'lbwp' ),
        ));

        /*
        * SECTION: Card Templates
        */

        $wp_customize->add_section( 'lbwp_section_template_card_1', array(
            'title' => __( 'Template: Cards', 'lbwp' ),
            'description' => __( 'Adjust how Live Blog WP Card template operates.', 'lbwp' ),
            'panel' => 'lbwp_panel_main',
            'priority' => 1,
        ));

        $wp_customize->add_setting( 'lbwp_options[template_card_1_avatars]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[template_card_1_avatars]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Show Avatars', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' )
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_authors]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[template_card_1_authors]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Show Authors', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' )
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_author_prefix]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
        ));

        $wp_customize->add_control( 'lbwp_options[template_card_1_author_prefix]', array(
            'type' => 'text',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Author Prefix Text', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[template_card_1_headings]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[template_card_1_headings]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Show Headings', 'lbwp' ),
            'choices' => array(
                '1' => __( 'Yes', 'lbwp' ),
                '0' => __( 'No', 'lbwp' )
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_heading_tag]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => 'h4',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[template_card_1_heading_tag]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Heading Tag', 'lbwp' ),
            'choices' => array(
                'div' => __( 'DIV', 'lbwp' ),
                'h1' => __( 'H1', 'lbwp' ),
                'h2' => __( 'H2', 'lbwp' ),
                'h3' => __( 'H3', 'lbwp' ),
                'h4' => __( 'H4','lbwp' ),
                'h5' => __( 'H5', 'lbwp' ),
                'h6' => __( 'H6', 'lbwp' ),
            )
        ) );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_background_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[template_card_1_background_color]', array(
                'label' => __( 'Background Color', 'lbwp' ),
                'section' => 'lbwp_section_template_card_1',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_border_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[template_card_1_border_color]', array(
                'label' => __( 'Border Color', 'lbwp' ),
                'section' => 'lbwp_section_template_card_1',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_border_width]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[template_card_1_border_width]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Border Width (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[template_card_1_icon_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#000000',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[template_card_1_icon_color]', array(
                'label' => __( 'Icon Color', 'lbwp' ),
                'section' => 'lbwp_section_template_card_1',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_icon_hover_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#1e87f0',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[template_card_1_icon_hover_color]', array(
                'label' => __( 'Icon Hover Color', 'lbwp' ),
                'section' => 'lbwp_section_template_card_1',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_icon_size]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '1.2',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_float',
        ));

        $wp_customize->add_control( 'lbwp_options[template_card_1_icon_size]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Icon Size', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[template_card_1_time_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#000000',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[template_card_1_time_color]', array(
                'label' => __( 'Time Color', 'lbwp' ),
                'section' => 'lbwp_section_template_card_1',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_time_size]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '14',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[template_card_1_time_size]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Padding (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[template_card_1_author_color]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#000000',
        ) );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control( $wp_customize, 'lbwp_options[template_card_1_author_color]', array(
                'label' => __( 'Author Color', 'lbwp' ),
                'section' => 'lbwp_section_template_card_1',
                'priority' => 1
            ) )
        );

        $wp_customize->add_setting( 'lbwp_options[template_card_1_author_size]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '14',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[template_card_1_author_size]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Padding (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[template_card_1_padding]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[template_card_1_padding]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Padding (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[template_card_1_margin_bottom]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '20',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control( 'lbwp_options[template_card_1_margin_bottom]', array(
            'type' => 'number',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Margin Bottom (px)', 'lbwp' ),
        ));

        $wp_customize->add_setting( 'lbwp_options[template_card_1_box_shadow]', array(
            'type' => 'option',
            'capability' => 'manage_options',
            'default' => '0',
            'sanitize_callback' => '\Live_Blog_WP\Customizer\Customizer::sanitize_select',
        ) );

        $wp_customize->add_control( 'lbwp_options[template_card_1_box_shadow]', array(
            'type' => 'select',
            'priority' => 1,
            'section' => 'lbwp_section_template_card_1',
            'label' => __( 'Box Shadow', 'lbwp' ),
            'choices' => array(
                '0' => __( 'None', 'lbwp' ),
                'uk-box-shadow-small' => __( 'Small', 'lbwp' ),
                'uk-box-shadow-medium' => __( 'Medium', 'lbwp' ),
                'uk-box-shadow-large' => __( 'Large', 'lbwp' ),
            )
        ) );


    }

    public static function sanitize_checkbox( $input ) {
        return ( isset( $input ) ? true : false );
    }

    public static function sanitize_select( $input, $setting ) {
        $input = sanitize_text_field($input);
        $choices = $setting->manager->get_control( $setting->id )->choices;
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
    }

    public static function sanitize_float( $input ) {
        return ( is_float( $input ) ? true : false );
    }
}
