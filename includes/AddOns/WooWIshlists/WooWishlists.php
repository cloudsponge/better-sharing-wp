<?php

namespace BetterSharingWP\AddOns\WooWishlists;

use BetterSharingWP\AddOns\BetterSharingAddOn;

class WooWishlists extends  BetterSharingAddOn
{
    /**
     * Init
     *
     * @return mixed
     */
    public function init()
    {

        $initReturn = parent::initAddOn(
            'WooCommerce WishLists',
            'Better Sharing WP AddOn for WooCommerce WishLists',
            false
        );

        $this->support_url = 'https://cloudsponge.com';

        if ($this->is_active() ) {
            add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
            remove_action('wp_footer', 'woocommerce_wishlist_render_email_forms', 10);
            add_action('wp_footer', [ $this, 'wishlist_email_form' ], 10);
        }

        return is_wp_error($initReturn) ? $initReturn : $this->is_active();
    }

    /**
     * Check if Coupon Referral Program is Installed & Active
     *
     * @return bool
     */
    public function is_plugin_active()
    {
        return class_exists('WC_Wishlists_Plugin');
    }

    /**
     * Enqueue Scripts
     *
     * @return bool
     */
    public function enqueue_scripts()
    {
        global $post;

        $wishListPageId = (int) get_site_option('wc_wishlists_page_id_my-lists', false);

        if ($wishListPageId !== $post->ID && $wishListPageId !== $post->post_parent ) {
            return false;
        }

        //        wp_enqueue_script(
        //                'bootstrap-modal-wishlist-addon',
        //                get_site_url() . '/wp-content/plugins/woocommerce-wishlists/assets/js/bootstrap-modal.js',
        //                array( 'jquery' ),
        //            BETTER_SHARING_VERSION,
        //                true
        //        );

        wp_enqueue_script(
            'cloudsponge-js',
            '//api.cloudsponge.com/widget/' . $this->api_key . '.js',
            [ 'jquery' ],
            BETTER_SHARING_VERSION,
            false
        );

        wp_enqueue_script(
            'bswp-addons-automatewoo',
            BETTER_SHARING_URI . 'dist/addons/woowishlist.js',
            ['cloudsponge-js'],
            BETTER_SHARING_VERSION,
            false
        );
    }

    /**
     * Wishlist Email Form - Modal
     */
    public function wishlist_email_form()
    {
        global $email_forms;
        $api_key = get_site_option('_bswp_option_core_apiKey', false);

        if ($email_forms && !empty($email_forms) ) :

            foreach ( $email_forms as $wishlist ) {
                ?>

                <div class="wl-modal" id="share-via-email-<?php echo $wishlist->id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;z-index:9999;">
                    <div class="wl-modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h1 id="myModalLabel"><?php _e('Share this list via e-mail ', 'wc_wishlist'); ?></h1>
                    </div>
                    <div class="wl-modal-body">
                        <form id="share-via-email-<?php echo $wishlist->id; ?>-form" action="" method="POST">
                            <p class="form-row form-row-wide" class="wishlist_name">
                                <label for="wishlist_email_from"><?php _e('Your name:', 'wc_wishlist'); ?></label>
                                <input type="text" class="input-text" name="wishlist_email_from" value="<?php echo esc_attr(get_post_meta($wishlist->id, '_wishlist_first_name', true) . ' ' . get_post_meta($wishlist->id, '_wishlist_last_name', true)); ?>"/>
                            </p>
                            <p class="form-row form-row-wide">
                                <label for="wishlist_email_to"><?php _e('To:', 'wc_wishlist'); ?></label>
                                <textarea class="wl-em-to" name="wishlist_email_to" rows="2" placeholder="<?php _e('Type in e-mail addresses: jo@example.com, jan@example.com.', 'wc_wishlist'); ?>"></textarea>

                <?php if ($api_key ) : ?>
                                    <div class="text-right">
                                        <a href="#" class="add-from-address-book-init btn button">
                                            <span class="dashicons dashicons-book-alt"></span>
                                            <?php esc_attr_e('Add From Address Book', 'better-sharing-wp'); ?>
                                        </a>
                                    </div>
                <?php endif; ?>

                            </p>
                            <p class="form-row form-row-wide">
                                <label for="wishlist_content"><?php _e('Add a note:', 'wc_wishlist'); ?></label>
                                <textarea class="wl-em-note" name="wishlist_content" rows="4"></textarea>
                            </p>
                            <div class="clear"></div>
                            <input type="hidden" name="wishlist_id" value="<?php echo esc_attr($wishlist->id); ?>"/>
                            <input type="hidden" name="wishlist-action" value="share-via-email"/>
                <?php echo \WC_Wishlists_Plugin::nonce_field('share-via-email') ?>
                        </form>
                    </div>
                    <div class="wl-modal-footer">
                        <button class="button alt share-via-email-button" data-form="share-via-email-<?php echo $wishlist->id; ?>-form" aria-hidden="true"><?php _e('Send email', 'wc_wishlist'); ?></button>
                    </div>
                </div>

                <?php
            }

        endif;
    }
}