<?php

namespace BetterSharingWP;

use BetterSharingWP\AddOnsCore;

$addOns = AddOnsCore::getAddOns();

?>
<div class="bswp-container addons">
    <?php foreach( $addOns as $addOn ) : ?>
        <div class="bswp-single-addon status-<?php echo $addOn->status; ?>">
            <h2><?php echo $addOn->name; ?>: <?php echo $addOn->status; ?></h2>

            <!-- Description -->
            <?php if ( $addOn->description ) : ?>
                <div class="bswp-single-addon-description">
                    <?php echo wpautop( $addOn->description ); ?>
                </div>
            <?php endif; ?>
            <!-- /Description -->

            <!-- Doc / Support URL -->
            <?php if ( $addOn->supportUrl ) : ?>
                <div class="bswp-single-addon-link">
                    <strong>AddOn Link:</strong>
                    <a href="<?php echo $addOn->supportUrl; ?>" target="_blank">
                        <?php echo $addOn->supportUrl; ?>
                        <span class="dashicons dashicons-external"></span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Toggle -->
            <div class="bswp-single-addon-status-wrapper">

                <span class="bswp-single-addon-status-title">
                    <?php
                        if ( 'active' === $addOn->status && $addOn->isPluginActive() ) {
                            echo 'active';
                        } elseif ( 'active' !== $addOn->status && $addOn->isPluginActive() ) {
                            echo 'inactive';
                        } elseif ( ! $addOn->isPluginActive() ) {
                            echo '<p style="text-align:center;font-size:0.8em;">Plugin is not installed & activated. Go to the Plugins page to activate the appropriate plugin</p>';
                        }
                    ?>
                </span>
                <?php if ( $addOn->isPluginActive() ) : ?>
                    <div
                            class="bswp-single-addon-status-toggle <?php echo $addOn->isPluginActive() ? 'plugin-active' : 'plugin-inactive'; ?>"
                            data-addon="<?php echo $addOn->slug; ?>"
                            data-status="<?php echo $addOn->status; ?>"
                            data-plugin="<?php echo $addOn->isPluginActive() ? 'active' : 'inactive'; ?>"
                    >
                    <span
                            class="bswp-single-addon-status-btn"
                            title="<?php echo 'active' === $addOn->status ? 'deactivate' : 'activate'; ?>"
                    >
                    </span>
                    </div>
                <?php endif; ?>

                <!-- Settings Toggle -->
	            <?php if ( $addOn->hasSettings ) : ?>
                    <a class="btn button bswp-single-addon-settings-toggle" href="#" data-addon="<?php echo $addOn->slug; ?>">Settings</a>
	            <?php endif; ?>
                <!-- / Settings Toggle -->
            </div>
            <!-- /Toggle -->

            <!-- Settings -->
	        <?php if ( $addOn->hasSettings ) : ?>
                <div class="bswp-single-addon-settings-wrapper <?php echo $addOn->slug . '-settings'; ?>">
                    <form method="post" action="<?php echo admin_url('admin.php?page=better-sharing-addons'); ?>">
                        <input type="hidden" name="save_addon" value="yes"/>
                        <hr/>
                        <div class="bswp-single-addon-settings-inner">
                            <?php $addOn->displaySettings(); ?>
                        </div>
                        <input class="btn button" type="submit" value="Save Settings" />
                    </form>
                </div>
	        <?php endif; ?>
            <!-- /Settings -->

        </div>
    <?php endforeach; ?>
</div>