<?php

namespace Live_Blog_WP\Single_Templates;

if ( ! defined( 'ABSPATH' ) ) exit;

class Full_Width {

    public static function render() {

        $options = \Live_Blog_WP\Instance::$options;

        ?>

        <?php \Live_Blog_WP\Single_Templates\Header::render(); ?>

        <?php \Live_Blog_WP\Single_Templates\Alert::render(); ?>

        <div class="lbwp-wrapper">

            <div id="lbwp-top"></div>

            <?php \Live_Blog_WP\Single_Templates\Spinner::render(); ?>

            <div id="lbwp-inview-trigger"></div>
            <div id="lbwp-posts"></div>

            <div id="lbwp-bottom"></div>

        </div>


        <?php

    }
}
