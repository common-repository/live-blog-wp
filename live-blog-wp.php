<?php
/**
* Plugin Name: Live Blog WP
* Description: Turn any post into a real time auto updating live blog.
* Plugin URI:  https://liveblogwp.com
* Version:     1.0.5
* Author:      Live Blog WP
* Text Domain: lbwp
*/

namespace Live_Blog_WP;

if ( ! defined( 'ABSPATH' ) ) exit;

define('LIVE_BLOG_WP', true );
define('LIVE_BLOG_WP_PATH', plugin_dir_path( __FILE__ ) );

/**
* The main class that initiates and runs the plugin.
*/

class Instance {

    /*
    * Holds the plugins current version
    */
    const VERSION = '1.0.5';

    /*
    * The minimum required PHP version
    */
    const MINIMUM_PHP_VERSION = '7.0';

    /*
    * The minimum required WP version
    */
    const MINIMUM_WP_VERSION = '5.0';

    /*
    * A place to cache the stored options
    */
    public static $options = [];

    /*
    * Instance
    */
    private static $_instance = null;

    /*
    * Instance
    */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    /*
    * Constructor
    */
    public function __construct() {
        $this->register_autoloader();
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    /*
    * Register Autoloader
    */
    private function register_autoloader() {
        spl_autoload_register( [ $this, 'autoload' ] );
    }

    /*
    * Autoload
    */
    public function autoload( $class ) {
        if ( strpos( $class, 'Live_Blog_WP\\' ) !== false ) {
            if ( ! class_exists( $class ) ) {
                $filename = str_replace( 'Live_Blog_WP\\', '', $class );
                $filename = str_replace( '\\', '/', $filename );
                $filename = str_replace( '_', '-', $filename );
                $filename = strtolower( $filename );
                $filename = LIVE_BLOG_WP_PATH . $filename . '.php';
                if ( is_readable( $filename ) ) {
                    include_once( $filename );
                }
            }
        }
    }

    /*
    * Initialize the plugin
    */
    public function init() {

        global $wp_version;

        // Check for required PHP version
        if ( ! version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Check for required WP version
        if ( ! version_compare( $wp_version, self::MINIMUM_WP_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_wp_version' ] );
            return;
        }

        // Check for required WP version
        if ( ! class_exists( 'ACF' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_acf' ] );
            return;
        }

        // add acf fields if acf is installed and activate
        add_action('acf/init', [ $this, 'add_acf_fields' ] );

        // Add scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );

        // register needed custom post types
        add_action('init', [ $this, 'custom_post_types' ] );

        // register blocks
        add_action( 'init', [ $this, 'add_blocks' ] );

        // AJAX handlers
        add_action( 'wp_ajax_nopriv_lbwp_get_posts', [ $this, 'get_posts' ] );
        add_action( 'wp_ajax_lbwp_get_posts', [ $this, 'get_posts' ] );

        // customizer
        add_action( 'customize_register', '\Live_Blog_WP\Customizer\Customizer::register' );
        add_action( 'wp_head' , [ $this, 'output_customizer_css' ], 100 );

        // options
        self::$options = $this->get_options();

        // boradcast the plugin was loaded
        do_action( 'lbwp/loaded' );

    }

    /*
    * Handles the front end display of the gutenberg block
    */
    public function block_callback( $block_attributes, $content ) {

        if ( is_singular( 'post' ) ) {

            $options = $this->get_options();

            ob_start();

            $local_template = get_field( 'lbwp_single_template', get_the_ID() );

            // check for legacy templates having no value
            if ( empty( $local_template ) || $local_template == 'global' ) {

                $single_template = $options['single_template'];

            } else {

                $single_template = $local_template;

            }

            // apply the selected template
            if ( $single_template == 'full_width' ) {

                if ( class_exists( '\Live_Blog_WP_Pro\Single_Templates\Full_Width' ) ) {

                    \Live_Blog_WP_Pro\Single_Templates\Full_Width::render();

                } else {

                    \Live_Blog_WP\Single_Templates\Full_Width::render();

                }

            }

            if ( $single_template == 'sidebar' ) {

                if ( class_exists( '\Live_Blog_WP_Pro\Single_Templates\Sidebar' ) ) {

                    \Live_Blog_WP_Pro\Single_Templates\Sidebar::render();

                } else {

                    \Live_Blog_WP\Single_Templates\Sidebar::render();

                }

            }

            return ob_get_clean();

        } else {

            return;

        }

    }

    /*
    * Adds the gutenberg block
    */
    public function add_blocks() {

        wp_register_script(
            'lbwp-live-blog',
            plugins_url( 'blocks/live-blog.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'blocks/live-blog.js' )
        );

        register_block_type( 'lbwp/post', array(
            'editor_script' => 'lbwp-live-blog',
            'render_callback' => [ $this, 'block_callback' ]
        ) );

    }

    /*
    * Sends an error response to the front end when getting posts fails
    */
    public function get_posts_error( $message ) {

        $return['has_error'] = true;
        $return['error_message'] = $message;

        echo json_encode( $return );

        wp_die();

    }

    /*
    * Gets new entries to display on a live blog post
    */
    public function get_posts() {

        /*
        * Sanity checks for front end data
        */

        // did we get an array of child post ids from front end
        // on the first load we expect the array to exist and contain [0]
        if ( ! is_array( $_POST['lbwp_child_ids'] ) ) {
            $this->get_posts_error( 'child_ids_not_array' );
        }

        // are all the used id values from front end numeric
        foreach ( $_POST['lbwp_child_ids'] as $k => $v ) {
            if ( ! is_numeric( $v ) ) {
                $this->get_posts_error( 'child_id_not_numeric' );
            }
        }

        // does the parent post id exist
        if ( ! is_string( get_post_status( absint( $_POST['lbwp_parent_id'] ) ) ) ) {
            $this->get_posts_error( 'parent_id_not_found' );
        }

        /*
        * From here the data from the front end is considered sane
        */

        // sanitize the parent post id front front end
        $parent_id = absint( $_POST['lbwp_parent_id'] );

        // sanitize the child ids from front end
        $child_ids = [];
        foreach ( $_POST['lbwp_child_ids'] as $k => $v) {
            $child_ids[] = absint( $v );
        }

        /*
        * Get ready to get new posts based on the parent post id and used post ids
        */

        $options = $this->get_options();

        // setup returned data defaults
        $return['html'] = '';
        $return['points_html'] = '';
        $return['pinned_html'] = '';
        $return['has_new'] = false;
        $return['new_count'] = 0;
        $return['has_error'] = false;
        $return['child_ids'] = $child_ids;

        /*
        * Check for pinned html
        */
        if ( $pinned_post = get_field( 'lbwp_pinned_post', $parent_id ) ) {

            $return['pinned_html'] = apply_filters( 'the_content', get_the_content( '', false, $pinned_post ) );

        }

        // build the query arguemnts to try and get new posts
        $new_posts_args = [
            'posts_per_page'   => -1,
            'fields'	       => 'ids',
            'post_type'		   => 'lbwp_post',
            'meta_key'		   => 'lbwp_attached_post',
            'meta_value'	   => $parent_id,
            'post__not_in'     => $child_ids,
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_status'      => 'publish'
        ];

        /*
        * Get any new posts since last check
        */
        $new_posts = get_posts( $new_posts_args );

        // if we find new posts to use
        if ( ! empty( $new_posts ) ) {

            // update returned data defaults
            $return['has_new'] = true;
            $return['new_count'] = count( $new_posts );
            $return['child_ids'] = array_merge( $child_ids, $new_posts );

            // check for local post template override
            $local_template = get_field( 'lbwp_post_template', $parent_id );

            // check for legacy templates having no value
            if ( empty( $local_template ) || $local_template == 'global' ) {

                $single_template = $options['post_template'];

            } else {

                $single_template = $local_template;

            }

            // start buffering temaplate output
            ob_start();

            foreach ( $new_posts as $k => $v ) {

                $wrapper_classes = [];

                if ( $single_template == 'card_1' ) {

                    $wrapper_classes[] = 'lbwp-post-template-card-1-done';

                    if ( ! empty( $options['template_card_1_box_shadow'] ) ) {

                        $wrapper_classes[] = $options['template_card_1_box_shadow'];

                    }

                    \Live_Blog_WP\Post_Templates\Card_1::render( $v, $wrapper_classes, $parent_id );

                }

                if ( $single_template == 'card_2' ) {

                    $wrapper_classes[] = 'lbwp-post-template-card-2-done';

                    if ( ! empty( $options['template_card_2_box_shadow'] ) ) {

                        $wrapper_classes[] = $options['template_card_2_box_shadow'];

                    }

                    \Live_Blog_WP\Post_Templates\Card_2::render( $v, $wrapper_classes, $parent_id );

                }

            }

            // append the buffer to the default html
            $return['html'] .= ob_get_clean();

        }

        // if the pro version is installed return some html for the points
        if ( class_exists( '\Live_Blog_WP_Pro\Points\Points' ) ) {

            $return['points_html'] = \Live_Blog_WP_Pro\Points\Points::render( $parent_id );

        }

        // send the output
        echo json_encode( $return );

        // done
        wp_die();

    }

    /*
    * Adds needed post types
    */
    public function custom_post_types() {

        do_action( 'lbwp/register-taxonomies' );

        $posts_args = [
            'labels' => [
                'name' => __('Live Blog Posts', 'lbwp'),
                'singular_name' => __('Live Blog Posts', 'lbwp'),
            ],
            'public' => true,
            'has_archive' => false,
            'show_in_rest' => true,
            'taxonomies' => [ 'lbwp_post_tags', 'lbwp_post_cats' ],
            'publicly_queryable'  => false,
            'supports' => [ 'title', 'editor' ]
        ];

        register_post_type('lbwp_post',  $posts_args );

    }

    /*
    * Adds the acf fields if the acf init action was done.
    */
    public function add_acf_fields() {

        \Live_Blog_WP\Acf\Acf::add_fields();

    }

    /*
    * Used to tag strings as Pro
    */
    public function pro_tag() {

        if ( ! defined( 'LIVE_BLOG_WP_PRO' ) ) {

            return __( ' (Pro)', 'lbwp' );

        } else {

            return '';

        }

    }

    /*
    * Includes css and js.
    */
    public function scripts() {

        wp_enqueue_style( 'lbwp-uikit', plugins_url( '/assets/lbwp-uikit.css', __FILE__ ), [], self::VERSION );
        wp_enqueue_script( 'lbwp-timeago', plugins_url( '/assets/lbwp-timeago.js', __FILE__ ), [ 'jquery' ], self::VERSION );
        wp_enqueue_script( 'lbwp-uikit', plugins_url( '/assets/lbwp-uikit.js', __FILE__ ), [], self::VERSION );
        wp_enqueue_script( 'lbwp-uikit-icons', plugins_url( '/assets/lbwp-uikit-icons.js', __FILE__ ), [], self::VERSION );
        wp_enqueue_script( 'lbwp-clipboard', plugins_url( '/assets/clipboard.min.js', __FILE__ ), [], self::VERSION );

    }

    /*
    * Puts the customizer CSS in the head.
    */
    public function output_customizer_css() {

        $options = $this->get_options('lbwp_options');

        ?>

        <style type="text/css">
        #lbwp-spinner {
            color: <?php echo sanitize_hex_color( $options['spinner_color'] ); ?>;
            margin-top: 20px;
        }
        .lbwp-post-template-card {
            background-color: <?php echo sanitize_hex_color( $options['template_card_1_background_color'] ); ?>;
            border-color: <?php echo sanitize_hex_color( $options['template_card_1_border_color'] ); ?>;
            border-width: <?php echo absint( $options['template_card_1_border_width'] ); ?>px;
            border-style: solid;
            padding: <?php echo absint( $options['template_card_1_padding'] ); ?>px;
            margin-bottom: <?php echo absint( $options['template_card_1_margin_bottom'] ); ?>px;
        }
        .lbwp-post-template-card .lbwp-time {
            color: <?php echo sanitize_hex_color( $options['template_card_1_time_color'] ); ?>;
            font-size: <?php echo absint( $options['template_card_1_time_size'] ); ?>px;
        }
        .lbwp-post-template-card .lbwp-author {
            color: <?php echo sanitize_hex_color( $options['template_card_1_author_color'] ); ?>;
            font-size: <?php echo absint( $options['template_card_1_author_size'] ); ?>px;
        }
        .lbwp-post-template-card .lbwp-card-icon {
            color: <?php echo sanitize_hex_color( $options['template_card_1_icon_color'] ); ?>;
        }
        .lbwp-post-template-card .lbwp-card-icon:hover, .lbwp-post-template-card .lbwp-card-icon:focus {
            color: <?php echo  sanitize_hex_color( $options['template_card_1_icon_hover_color'] ); ?>;
        }
        #lbwp-toolbar {
            background-color: <?php echo sanitize_hex_color( $options['toolbar_background_color'] ); ?>;
            border-color: <?php echo sanitize_hex_color( $options['toolbar_border_color'] ); ?>;
            border-width: <?php echo absint( $options['toolbar_border_width'] ); ?>px;
            border-style: solid;
            padding: <?php echo absint( $options['toolbar_padding'] ); ?>px;
            z-index: <?php echo absint( $options['toolbar_z_index'] ); ?>;
        }
        #lbwp-toolbar span, #lbwp-toolbar a {
            color: <?php echo sanitize_hex_color( $options['toolbar_icon_color'] ); ?> !important;
        }
        #lbwp-toolbar span:hover, #lbwp-toolbar span:focus, #lbwp-toolbar a:hover, #lbwp-toolbar a:focus {
            color: <?php echo sanitize_hex_color( $options['toolbar_icon_hover_color'] ); ?> !important;
        }
        #lbwp-toolbar #lbwp-new-label.lbwp-no-new {
            display: none;
        }
        #lbwp-toolbar #lbwp-new-label.lbwp-has-new {
            display: inline-block;
        }
        #lbwp-toolbar #lbwp-new-label {
            color: <?php echo sanitize_hex_color( $options['toolbar_icon_color'] ); ?> !important;
            line-height: 1 !important;
        }
        #lbwp-toolbar #lbwp-new-label:hover {
            color: <?php echo sanitize_hex_color( $options['toolbar_icon_hover_color'] ); ?> !important;
            cursor: default;
        }
        #lbwp-spinner {
            color: <?php echo sanitize_hex_color( $options['spinner_color'] ); ?>;
        }
        #lbwp-points {
            margin-bottom: <?php echo absint( $options['points_wrap_margin_bottom'] ); ?>px;
        }
        #lbwp-points .lbwp-point {
            margin-bottom: <?php echo absint( $options['points_margin_bottom'] ); ?>px;
        }
        #lbwp-points .lbwp-point .lbwp-time {
            color: <?php echo sanitize_hex_color($options['points_time_color'] ); ?>;
        }
        #lbwp-points .lbwp-point a {
            color: <?php echo sanitize_hex_color($options['points_link_color'] ); ?>;
        }
        #lbwp-points .lbwp-point a:hover, #lbwp-points .lbwp-point a:focus {
            color: <?php echo sanitize_hex_color( $options['points_link_hover_color'] ); ?>;
        }
        #lbwp-posts {
            padding-top: 20px;
        }
        #lbwp-alert.lbwp-force-show {
            visibility: visible !important;
        }
        #lbwp-alert {
            background-color: <?php echo sanitize_hex_color( $options['alert_background_color'] ); ?>;
            color: <?php echo sanitize_hex_color( $options['alert_text_color'] ); ?>;
            border-style: solid;
            border-width: <?php echo absint( $options['alert_border_width'] ); ?>px;
            border-color: <?php echo sanitize_hex_color($options['alert_border_color'] ); ?>;
            padding: <?php echo absint( $options['alert_top_bottom_padding'] ); ?>px  <?php echo absint( $options['alert_left_right_padding'] ); ?>px;
            z-index: 9999;
        }
        #lbwp-pinned {
            margin-bottom: <?php echo absint( $options['pinned_margin_bottom'] ); ?>px;
        }
        </style>

        <?php

    }


    /*
    * Holds the defaults customizer options if they dont exist.
    */
    public function get_options() {

        $defaults = array(
            'single_template' => 'full_width',
            'post_template' => 'card_1',
            'show_toolbar' => '1',
            'support_blog_page' => false,
            'support_blog_text' => 'SUPPORT',
            'get_posts_interval' => 60,
            'step_offset' => 65,
            'label_js_suffixAgo' => 'ago',
            'label_js_seconds' => 'less than a minute',
            'label_js_minute' => 'about a minute',
            'label_js_minutes' => '%d minutes',
            'label_js_hour' => 'about an hour',
            'label_js_hours' => 'about %d hours',
            'label_js_day' => 'about a day',
            'label_js_days' => '%d days',
            'label_js_month' => 'about a month',
            'label_js_months' => '%d months',
            'label_js_year' => 'about a year',
            'label_js_years' => '%d years',
            'toolbar_background_color' => '#1e87f0',
            'toolbar_icon_color' => '#ffffff',
            'toolbar_icon_hover_color' => '#e5e5e5',
            'toolbar_icon_size' => '1.2',
            'toolbar_border_color' => '#1e87f0',
            'toolbar_border_width' => '0',
            'toolbar_padding' => '15',
            'toolbar_box_shadow' => '0',
            'toolbar_z_index' => '980',
            'toolbar_offset' => '0',
            'toolbar_new_text' => 'NEW POSTS',
            'toolbar_new_text_size' => '18',
            'spinner_color' => '#1e87f0',
            'spinner_size' => '2',
            'show_points' => '1',
            'show_points_heading' => '1',
            'show_points_time' => '1',
            'points_text' => 'Key Points',
            'points_heading_tag' => 'h4',
            'points_columns' => 'uk-child-width-1-3@m',
            'points_link_tag' => 'h5',
            'points_time_color' => '#000000',
            'points_link_color' => '#1e87f0',
            'points_link_hover_color' => '#1e87f0',
            'points_margin_bottom' => '5',
            'points_wrap_margin_bottom' => '15',
            'show_alert' => '1',
            'force_show_alert' => '0',
            'alert_text' => 'NEW POSTS',
            'alert_tag' => 'div',
            'alert_background_color' => '#ffffff',
            'alert_text_color' => '#000000',
            'alert_text_hover_color' => '#000000',
            'alert_border_color' => '#000000',
            'alert_border_width' => '1',
            'alert_top_bottom_padding' => '10',
            'alert_left_right_padding' => '20',
            'alert_box_shadow' => '0',
            'alert_position' => 'uk-position-bottom-right',
            'tab_text' => '(NEW POSTS)',
            'show_pinned' => '0',
            'pinned_margin_bottom' => '20',
            'template_card_1_avatars' => '1',
            'template_card_1_authors' => '1',
            'template_card_1_author_prefix' => '',
            'template_card_1_headings' => '0',
            'template_card_1_heading_tag' => 'h4',
            'template_card_1_background_color' => '#ffffff',
            'template_card_1_border_color' => '#ffffff',
            'template_card_1_border_width' => '0',
            'template_card_1_icon_color' => '#000000',
            'template_card_1_icon_hover_color' => '#1e87f0',
            'template_card_1_icon_size' => '1.2',
            'template_card_1_time_color' => '#000000',
            'template_card_1_author_color' => '#000000',
            'template_card_1_box_shadow' => '0',
            'template_card_1_padding' => '0',
            'template_card_1_margin_bottom' => '20',
            'template_card_1_time_size' => '14',
            'template_card_1_author_size' => '14',

        );

        return wp_parse_args( get_option( 'lbwp_options' ), $defaults );

    }

    /*
    * Warning when the site doesn't have a minimum required PHP version.
    */
    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'lbwp' ),
            '<strong>' . esc_html__( 'Live BLog WP', 'lbwp' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'lbwp' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );

    }

    /*
    * Warning when the site doesn't have a minimum required WP version.
    */
    public function admin_notice_minimum_wp_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'lbwp' ),
            '<strong>' . esc_html__( 'Live Blog WP', 'lbwp' ) . '</strong>',
            '<strong>' . esc_html__( 'WordPress', 'lbwp' ) . '</strong>',
            self::MINIMUM_WP_VERSION
        );

        printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );

    }

    /*
    * Warning when the site doesn't ACF.
    */
    public function admin_notice_missing_acf() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s".', 'lbwp' ),
            '<strong>' . esc_html__( 'Live Blog WP', 'lbwp' ) . '</strong>',
            '<strong>' . esc_html__( 'Advanced Custom Fields.', 'lbwp' ) . '</strong>'
        );

        printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );

    }

}

\Live_Blog_WP\Instance::instance();
