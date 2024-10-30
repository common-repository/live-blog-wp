<?php

namespace Live_Blog_WP\Single_Templates;

if ( ! defined( 'ABSPATH' ) ) exit;

class Spinner {

    public static function render() {

        $options = \Live_Blog_WP\Instance::$options;

        ?>

        <div id="lbwp-spinner" class="lbwp-spinner uk-text-center">
            <span uk-spinner="ratio: <?php echo esc_attr( $options['spinner_size']); ?>"></span>
        </div>

        <?php

    }
}
