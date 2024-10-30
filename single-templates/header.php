<?php

namespace Live_Blog_WP\Single_Templates;

if ( ! defined( 'ABSPATH' ) ) exit;

class Header {

    public static function render() {

        $options = \Live_Blog_WP\Instance::$options;

        ?>

        <script type="text/javascript">

        (function($) {

            /*
            * Some globsl variables
            */
            var lbwp_child_ids = [0];
            var lbwp_first_run = true;

            <?php if ( defined( 'LIVE_BLOG_WP_PRO' ) ) : ?>
            var lbwp_original_page_title = $(document).find("title").text();
            <?php endif; ?>

            /*
            * The ajax call that runs on the timer
            */
            function lbwp_update() {
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: "<?php echo esc_url( admin_url('admin-ajax.php') ); ?>",
                    data : {
                        action: "lbwp_get_posts",
                        lbwp_parent_id: "<?php echo absint( get_the_ID() ); ?>",
                        lbwp_child_ids: lbwp_child_ids,
                    },
                    success: function( data, status, xhr ){
                        lbwp_update_callback( data );
                    }
                });
            }

            /*
            * The function the ajax runs after checking with the server
            */
            function lbwp_update_callback( data ) {

                // Store the page height
                var old_page_height = $(document).height();
                var old_scroll_position = $(window).scrollTop();

                // If the spinner is on the page remove it
                if ( $( '#lbwp-spinner' ).length ) {
                    $( '#lbwp-spinner' ).remove()
                }

                // Update the entire contents of the point element
                $("#lbwp-points").html( data.points_html );

                // Update the entire contents of the point element
                $("#lbwp-pinned").html( data.pinned_html );

                // Check if the server gave us new posts to add
                if ( data.has_new === true ) {
                    // update the used child IDs
                    lbwp_child_ids = data.child_ids;

                    $("#lbwp-posts").prepend( data.html );

                    if ( lbwp_first_run === false ) {
                        if ( $(document).scrollTop() > 0 ) {
                            $(document).scrollTop(old_scroll_position + jQuery(document).height() - old_page_height);
                        }
                    }

                    UIkit.update(element = document.body, type = 'update');

                    if ( lbwp_first_run === false ) {
                        lbwp_show_notifications();
                    }

                }

                // Update the timeago text
                lbwp_update_timeago();

                // Rebind listener for clicks
                lbwp_rebind_ui();

                // Check if we need to scoll to an item based on the URL
                if ( lbwp_first_run === true ) {
                    lbwp_scroll_to_item();
                }

                // Update the first run var to be false
                lbwp_first_run = false;

            }

            /*
            * Clears all alerts and other data from the screen
            */
            function lbwp_clear_screen() {
                <?php if ( defined( 'LIVE_BLOG_WP_PRO' ) ) : ?>
                $(document).prop('title', lbwp_original_page_title);
                <?php endif; ?>
                $('#lbwp-new-label').addClass('uk-invisible');
                $('#lbwp-alert').addClass('uk-invisible');
            }

            /*
            * Shows alerts and other notifications
            */
            function lbwp_show_notifications() {
                <?php if ( defined( 'LIVE_BLOG_WP_PRO' ) ) : ?>
                $(document).prop('title', '<?php echo esc_attr( $options['tab_text']); ?> ' + lbwp_original_page_title);
                <?php endif; ?>
                $('#lbwp-new-label').removeClass('uk-invisible');
                $('#lbwp-alert').removeClass('uk-invisible');
            }

            /*
            * Updates the timeago text for all feed items.
            */
            function lbwp_update_timeago() {
                // force update of timeago
                var timeago_settings = {
                    strings: {
                        prefixAgo: null,
                        prefixFromNow: null,
                        suffixAgo: "<?php echo esc_attr( $options['label_js_suffixAgo'] ); ?>",
                        suffixFromNow: "from now",
                        seconds: "<?php echo esc_attr( $options['label_js_seconds'] ); ?>",
                        minute: "<?php echo esc_attr( $options['label_js_minute'] ); ?>",
                        minutes: "<?php echo esc_attr( $options['label_js_minutes'] ); ?>",
                        hour: "<?php echo esc_attr(  $options['label_js_hour'] ); ?>",
                        hours: "<?php echo esc_attr( $options['label_js_hours'] ); ?>",
                        day: "<?php echo esc_attr( $options['label_js_day'] ); ?>",
                        days: "<?php echo esc_attr( $options['label_js_days'] ); ?>",
                        month: "<?php echo esc_attr( $options['label_js_month'] ); ?>",
                        months: "<?php echo esc_attr( $options['label_js_months'] ); ?>",
                        year: "<?php echo esc_attr( $options['label_js_year'] ); ?>",
                        years: "<?php echo esc_attr( $options['label_js_years'] ); ?>",
                        numbers: []
                    }
                }

                $("time.timeago").timeago(timeago_settings);
            }

            /*
            * Rebinds the UI click listeners after ajax.
            */
            function lbwp_rebind_ui() {

                // key point link
                $('.lbwp-point-link').off('click');
                $(".lbwp-point-link").click(function(e) {
                    e.preventDefault();
                    var keypoint_href = $(this).attr("href");
                    $('html,body').animate({scrollTop: $(keypoint_href).offset().top - <?php echo absint( $options['step_offset']); ?> }, 500);
                    return false;
                });

                // next post arrow
                $('.lbwp-next-post').off('click');
                $(".lbwp-next-post").on("click", function(e) {
                    var next_post = $($(this).closest('.lbwp-post')).next('.lbwp-post');
                    if ( next_post.length ) {
                        $('html,body').animate({scrollTop: $(next_post).offset().top - <?php echo absint( $options['step_offset']); ?> }, 500);

                    }
                    return false;
                });

                // prev post arrow
                $('.lbwp-prev-post').off('click');
                $(".lbwp-prev-post").on("click", function(e) {
                    var prev_post = $($(this).closest('.lbwp-post')).prev('.lbwp-post');
                    if ( prev_post.length ) {
                        $('html,body').animate({scrollTop: $(prev_post).offset().top - <?php echo absint( $options['step_offset']); ?> }, 500);
                    }
                    return false;
                });

                // copy url top clipboard
                var clipboard = new ClipboardJS('.lbwp-clipboard');

                if ( lbwp_first_run == false ) {
                    clipboard.destroy();
                    clipboard = new ClipboardJS('.lbwp-clipboard');
                }

                $('#lbwp-alert').off('click');
                $('#lbwp-alert').on('click', function() {
                    lbwp_scroll_to_first_item();
                    return false;
                });

                $('#lbwp-new-label').off('click');
                $('#lbwp-new-label').on('click', function() {
                    lbwp_scroll_to_first_item();
                    return false;
                });

            }

            /*
            * Do we need to scroll to an item from a URL parameter
            */
            function lbwp_scroll_to_item() {
                if ( lbwp_first_run === true ) {
                    var url_string = window.location.href;
                    var url = new URL(url_string);
                    var scroll_to_item = url.searchParams.get("lbwp-item");
                    if ( scroll_to_item === 'true' ) {
                        var item_to_scroll_to = $( '#lbwp-item-' + url.searchParams.get("lbwp-item-id") );
                        if ( lbwp_item_to_scroll_to.length ) {
                            $('html,body').animate({scrollTop: $(item_to_scroll_to).offset().top - <?php echo absint( $options['step_offset']); ?> }, 500);
                        }
                    }
                }
            }

            /*
            * Scrolls to the first item in the feed
            */
            function lbwp_scroll_to_first_item() {
                var first_item = $('#lbwp-posts').children().first();
                if ( first_item.length ) {
                    $('html,body').animate({scrollTop: $(first_item).offset().top - <?php echo esc_attr( $options['step_offset']); ?> }, 500);
                }
                lbwp_clear_screen();
            }

            /*
            * Scrolls to the last item in the feed
            */
            function lbwp_scroll_to_last_item() {
                var last_post = $('#lbwp-posts').children().last();
                if ( last_post.length ) {
                    $('html,body').animate({scrollTop: $(last_post).offset().top - <?php echo esc_attr( $options['step_offset']); ?> }, 800);
                }
            }

            /*
            * Get things started on page load
            */
            $( document ).ready(function() {

                setInterval(lbwp_update, <?php echo absint( $options['get_posts_interval'] * 1000 ); ?>);
                lbwp_update();

                var util = UIkit.util;
                var clear_screen_el = util.$( '#lbwp-inview-trigger' );

                UIkit.scrollspy( clear_screen_el, { repeat: true } );

                util.on(clear_screen_el,'inview', function() {
                    lbwp_clear_screen();
                });

                util.on(clear_screen_el,'outview', function() {
                    lbwp_clear_screen();
                });

                $('#lbwp-top-link').on('click', function() {
                    lbwp_scroll_to_first_item();
                });

                $('#lbwp-bottom-link').on('click', function() {
                    lbwp_scroll_to_last_item();
                });

            });

        })( jQuery );

        </script>

        <?php

    }
}
