<?php

namespace Live_Blog_WP\Acf;

if ( ! defined( 'ABSPATH' ) ) exit;

class Acf {

    public static function add_fields() {

        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_live_blog_wp_post',
                'title' => 'Live Blog WP',
                'fields' => array(
                    array(
                        'key' => 'field_lbwp_attached_post',
                        'label' => 'Attach to Post',
                        'name' => 'lbwp_attached_post',
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'post',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_lbwp_is_key_point',
                        'label' => 'Key Point',
                        'name' => 'lbwp_is_key_point',
                        'type' => 'true_false',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'lbwp_post',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'side',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => 'Live Blog WP Post Fields',
            ));

            acf_add_local_field_group(array(
                'key' => 'group_60315b5b1735c',
                'title' => 'Live Blog WP',
                'fields' => array(
                    array(
                        'key' => 'field_lbwp_pinned_post',
                        'label' => 'Pinned Post' . \Live_Blog_WP\Instance::instance()->pro_tag(),
                        'name' => 'lbwp_pinned_post',
                        'type' => 'post_object',
                        'instructions' => 'The pinned post is shown at the top the live blog feed, it updates at the same interval as the live blog but does not change position.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'post_type' => array(
                            0 => 'lbwp_post',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_lbwp_single_template',
                        'label' => 'Single Template',
                        'name' => 'lbwp_single_template',
                        'type' => 'select',
                        'instructions' => 'Force the use of a single template.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'global' => '-',
                            'full_width' => 'Full Width',
                            'sidebar' => 'Sidebar',
                        ),
                        'default_value' => 'global',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_lbwp_post_template',
                        'label' => 'Post Template',
                        'name' => 'lbwp_post_template',
                        'type' => 'select',
                        'instructions' => 'Force the use of a post template.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'global' => '-',
                            'card_1' => 'Card 1',
                            'card_2' => 'Card 2',
                        ),
                        'default_value' => 'global',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_lbwp_key_points',
                        'label' => 'Key Points',
                        'name' => 'lbwp_key_points',
                        'type' => 'select',
                        'instructions' => 'Show or hide key points.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'global' => '-',
                            'show' => 'Show',
                            'hide' => 'Hide',
                        ),
                        'default_value' => 'global',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                    ),
                    array(
                        'key' => 'field_lbwp_headings',
                        'label' => 'Headings',
                        'name' => 'lbwp_headings',
                        'type' => 'select',
                        'instructions' => 'Show or hide post headings.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array(
                            'global' => '-',
                            'show' => 'Show',
                            'hide' => 'Hide',
                        ),
                        'default_value' => 'global',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'post',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => true,
                'description' => '',
            ));

        endif;

    }

}
