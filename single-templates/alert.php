<?php

namespace Live_Blog_WP\Single_Templates;

if ( ! defined( 'ABSPATH' ) ) exit;

class Alert {

    public static function render() {

        $options = \Live_Blog_WP\Instance::$options;

        $alert_classes[] = 'lbwp-alert';
        $alert_classes[] = 'uk-invisible';

        if ( ! empty( $options['force_show_alert'] ) ) {
            $alert_classes[] = 'lbwp-force-show';
        }

        $alert_classes[] = 'uk-position-z-index';

        if ( ! empty( $options['alert_position'] ) ) {
            $alert_classes[] = $options['alert_position'];
        }

        $alert_classes[] = 'uk-position-fixed';
        $alert_classes[] = 'uk-position-small';

        if ( ! empty( $options['alert_box_shadow'] ) ) {
            $alert_classes[] = $options['alert_box_shadow'];
        }

        ?>

        <?php if ( ! empty( $options['show_alert'] ) ) : ?>

            <div id="lbwp-alert" class="<?php echo esc_attr( implode( ' ', $alert_classes ) ); ?>">
                <div class="uk-flex uk-flex-middle">
                    <span uk-icon="icon: chevron-up; ratio: 1;" class="uk-margin-small-right"></span>
                    <<?php echo esc_attr( $options['alert_tag']); ?> class="uk-margin-remove uk-display-inline">
                    <?php echo esc_html( $options['alert_text'] ); ?>
                    </<?php echo esc_attr( $options['alert_tag']); ?>>
                </div>
            </div>

        <?php endif; ?>

        <?php

    }
}
