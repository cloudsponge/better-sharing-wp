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

            <!-- Toggle -->
            <div class="bswp-single-addon-status-wrapper">

                <!-- Settings Toggle -->
                <?php if ( $addOn->hasSettings ) : ?>
                    <a class="btn button bswp-single-addon-settings-toggle" href="#" data-addon="<?php echo $addOn->slug; ?>">Settings</a>
                <?php endif; ?>
                <!-- / Settings Toggle -->

                <span class="bswp-single-addon-status-title">
	                <?php echo 'active' === $addOn->status ? 'active' : 'inactive'; ?>
                </span>
                <div class="bswp-single-addon-status-toggle" data-addon="<?php echo $addOn->slug; ?>" data-status="<?php echo $addOn->status; ?>">
                    <span
                        class="bswp-single-addon-status-btn"
                        title="<?php echo 'active' === $addOn->status ? 'deactivate' : 'activate'; ?>"
                    >
                    </span>
                </div>
            </div>
            <!-- /Toggle -->

            <!-- Settings -->
	        <?php if ( $addOn->hasSettings ) : ?>
                <div class="bswp-single-addon-settings-wrapper <?php echo $addOn->slug . '-settings'; ?>">
                    <hr/>
                    <div class="bswp-single-addon-settings-inner">
			            <?php $addOn->displaySettings(); ?>
                    </div>
                </div>
	        <?php endif; ?>
            <!-- /Settings -->

        </div>
    <?php endforeach; ?>
</div>