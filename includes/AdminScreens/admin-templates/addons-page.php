<?php

namespace BetterSharingWP;

use BetterSharingWP\AddOnsCore;

$addOns = AddOnsCore::getAddOns();

?>
<div class="bswp-container addons">
    <?php foreach( $addOns as $addOn ) : ?>
        <div class="bswp-single-addon status-<?php echo $addOn->status; ?>">
            <h2><?php echo $addOn->name; ?>: <?php echo $addOn->status; ?></h2>
            <?php if ( $addOn->description ) : ?>
                <div class="bswp-single-addon-description">
                    <?php echo wpautop( $addOn->description ); ?>
                </div>
            <?php endif; ?>

            <div class="bswp-single-addon-status-wrapper">
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
        </div>
    <?php endforeach; ?>
</div>