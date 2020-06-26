<?php

namespace BetterSharingWP;

use BetterSharingWP\AddOnsCore;

$addOns = AddOnsCore::getAddOns();

?>
<div class="bswp__container">
  <div class="bswp__addons">
    <?php foreach( $addOns as $addOn ) : ?>
    <div class="card bswp__addon status-<?php echo $addOn->status; ?>">

      <div class="bswp__addon__header">
        <h2 class="title bswp__addon__title"><?php echo $addOn->name; ?></h2>

        <!-- Toggle -->
        <div class="bswp__addon__status">
          <span class="bswp__addon__status-label">
            <?php
              if ( 'active' === $addOn->status && $addOn->isPluginActive() ) {
                echo 'Active';
              } elseif ( 'active' !== $addOn->status && $addOn->isPluginActive() ) {
                echo 'Inactive';
              } elseif ( !$addOn->isPluginActive() ) {
                echo '';
              }
            ?>
          </span>
          <?php if ( $addOn->isPluginActive() ) : ?>
          <div class="bswp__addon__status-indicator" data-addon="<?php echo $addOn->slug; ?>" data-status="<?php echo $addOn->status; ?>" data-plugin="<?php echo $addOn->isPluginActive() ? 'active' : 'inactive'; ?>">
          </div>
          <?php endif; ?>
        </div>
        <!-- /Toggle -->
      </div>

      <?php  
          if ( ! $addOn->isPluginActive() ) {
              echo '<p>Plugin is not installed & activated. Go to the Plugins page to activate the appropriate plugin</p>';
          } 
      ?>


      <!-- Description -->
      <?php if ( $addOn->description ) : ?>
      <div class="bswp__addon__description">
        <?php echo wpautop( $addOn->description ); ?>
      </div>
      <?php endif; ?>
      <!-- /Description -->

      <!-- Doc / Support URL -->
      <?php if ( $addOn->supportUrl ) : ?>
      <div class="bswp__addon__link">
        <strong>AddOn Link:</strong>
        <a href="<?php echo $addOn->supportUrl; ?>" target="_blank">
          <?php echo $addOn->supportUrl; ?>
          <span class="dashicons dashicons-external"></span>
        </a>
      </div>
      <?php endif; ?>

      <hr class="bswp__spacer">

      <!-- Settings Toggle -->
      <?php if ( $addOn->hasSettings ) : ?>
      <a class="btn button bswp__addon__settings-toggle" href="#" data-addon="<?php echo $addOn->slug; ?>">Settings</a>
      <?php endif; ?>
      <!-- / Settings Toggle -->



      <!-- Settings -->
      <?php if ( $addOn->hasSettings ) : ?>
      <div class="bswp__addon__settings <?php echo $addOn->slug . '-settings'; ?>">
        <form method="post" action="<?php echo admin_url('admin.php?page=better-sharing-addons'); ?>">
          <input type="hidden" name="save_addon" value="yes" />
          <div class="bswp__addon__settings-group">
            <?php $addOn->displaySettings(); ?>
          </div>
          <input class="button button-primary" type="submit" value="Save Settings" />
        </form>
      </div>
      <?php endif; ?>
      <!-- /Settings -->

    </div>
    <?php endforeach; ?>

  </div>
</div>