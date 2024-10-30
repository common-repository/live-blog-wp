<?php

namespace Live_Blog_WP\Post_Templates;

if ( ! defined( 'ABSPATH' ) ) exit;

class Card_2 {

    public static function render( $post_id, $wrapper_classes = [], $parent_id = 0 ) {

        $options = \Live_Blog_WP\Instance::$options;

        ?>

        <div class="lbwp-post lbwp-post-template-card <?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" id="lbwp-item-<?php echo absint( $post_id ); ?>">

            <div class="uk-width-1-1 uk-margin-small-bottom">

                <div class="uk-flex uk-flex-between uk-flex-top">

                    <div class="uk-width-expand">

                        <div>

                            <time style="line-height: 1 !important;" class="lbwp-time timeago uk-text-middle" datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>" itemprop="datePublished"></time>

                        </div>

                        <?php $local_headings_setting = get_field( 'lbwp_headings', $parent_id ); ?>

                        <?php if ( ( $local_headings_setting == 'show' ) || ( ( $local_headings_setting == 'global' || empty( $local_headings_setting ) ) && ! empty( $options['template_card_1_headings'] ) ) ) : ?>

                            <<?php echo esc_attr( $options['template_card_1_heading_tag']); ?> class="uk-margin-remove">

                            <?php echo esc_html( get_the_title( $post_id ) ); ?>

                            </<?php echo esc_attr( $options['template_card_1_heading_tag']); ?>>

                        <?php endif; ?>

                        <?php if ( ! empty( $options['template_card_1_authors'] ) ) : ?>

                            <div>

                                <div class="lbwp-author">

                                    <?php

                                    if ( !empty( $options['template_card_1_author_prefix'] ) ) {

                                        echo esc_html( $options['template_card_1_author_prefix'] . ' ' );

                                    }

                                    echo esc_html( get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ) );

                                    ?>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="uk-width-auto uk-margin-small-bottom uk-flex uk-flex-middle uk-flex-right">

                        <span class="lbwp-next-post lbwp-card-icon" uk-icon="icon: chevron-down; ratio: <?php echo esc_attr( $options['template_card_1_icon_size']); ?>;"></span>
                        <span class="lbwp-prev-post lbwp-card-icon uk-margin-small-left" uk-icon="icon: chevron-up; ratio: <?php echo esc_attr( $options['template_card_1_icon_size']); ?>;"></span>

                    </div>

                </div>

            </div>

            <div class="uk-width-1-1">

                <?php echo apply_filters( 'the_content', get_the_content( '', false, $post_id ) ); ?>

            </div>

            <div class="uk-width-auto uk-margin-small-top uk-flex uk-flex-between">

                <div>

                    <a style="line-height: 1;" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo urlencode( get_the_title( $parent_id ) ); ?>&url=<?php echo urlencode( get_permalink( $parent_id ) . '?lbwp-item=true&lbwp-item-id=' . $post_id ); ?>">
                        <span class="lbwp-card-icon uk-margin-small-right" uk-icon="icon: twitter; ratio: <?php echo esc_attr( $options['template_card_1_icon_size']); ?>;"></span>
                    </a>


                    <a style="line-height: 1;" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink( $parent_id ) . '?lbwp-item=true&lbwp-item-id=' . $post_id ); ?>">
                        <span class="lbwp-card-icon uk-margin-small-right" uk-icon="icon: facebook; ratio: <?php echo esc_attr( $options['template_card_1_icon_size']); ?>;"></span>
                    </a>

                    <a style="line-height: 1;" href="whatsapp://send?text=<?php echo urlencode( get_permalink( $parent_id ) . '?lbwp-item=true&lbwp-item-id=' . $post_id ); ?>" data-action="share/whatsapp/share">
                        <span class="lbwp-card-icon uk-margin-small-right" uk-icon="icon: whatsapp; ratio: <?php echo esc_attr( $options['template_card_1_icon_size']); ?>;"></span>
                    </a>

                </div>

                <div>

                    <span class="lbwp-card-icon lbwp-clipboard" data-clipboard-text="<?php echo esc_url( get_permalink( $parent_id ) . '?lbwp-item=true&lbwp-item-id=' . $post_id ); ?>" uk-tooltip="title: <?php echo __( 'Copy link to clipboard', 'lbwp' ); ?>; delay: 2000; pos: left;" uk-icon="icon: link; ratio: <?php echo esc_attr( $options['template_card_1_icon_size']); ?>;">
                    </span>

                </div>

            </div>

        </div>

        <?php

    }

}
