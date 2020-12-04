<?php

namespace BetterSharingWP;

use BetterSharingWP\AddOnsCore;

$addOns = AddOnsCore::getAddOns();

?>
<div class="bswp__container">
  <div class="bswp__addons">
    <?php foreach( $addOns as $addOn ) : ?>
        <?php 
        // Is Addon Active
        $addonActive = 'active' === $addOn->status;
        // Plugin is installed and activated - class
        $pluginAvailableClass = $addOn->is_plugin_active() ? 'plugin-available' : 'plugin-unavailable';
        // AddOn is active status - class
        $activeClass =  $addonActive && $addOn->is_plugin_active() ? 'active' : 'inactive';
        ?>
    <div class="card bswp__addon <?php echo $pluginAvailableClass; ?>">

      <div class="bswp__addon__header">
        <h2 class="title bswp__addon__title"><?php echo $addOn->name; ?></h2>

        <!-- Toggle -->
        <div class="bswp__addon__status">
          <span class="bswp__addon__status-label">
            <?php echo $activeClass; ?>
          </span>
          <!-- toggle slider -->
          <div class="bswp__addon__status-indicator <?php echo $activeClass; ?>" data-addon="<?php echo $addOn->slug; ?>" data-status="<?php echo $addOn->status; ?>" data-plugin="<?php echo $pluginAvailableClass; ?>"></div>
        </div>
        <!-- /Toggle -->
      </div>

        <?php  
        if (! $addOn->is_plugin_active() ) {
            echo '<p>Plugin is not installed & activated. Go to the Plugins page to activate the appropriate plugin</p>';
        } 
        ?>


      <!-- Description -->
        <?php if ($addOn->description ) : ?>
      <div class="bswp__addon__description">
            <?php echo wpautop($addOn->description); ?>
      </div>
        <?php endif; ?>
      <!-- /Description -->

      <!-- Doc / Support URL -->
        <?php if ($addOn->support_url ) : ?>
      <div class="bswp__addon__link">
        <strong>AddOn Link:</strong>
        <a href="<?php echo $addOn->support_url; ?>" target="_blank">
            <?php echo $addOn->support_url; ?>
          <span class="dashicons dashicons-external"></span>
        </a>
      </div>
        <?php endif; ?>

      <!-- Settings Toggle -->
        <?php if ($addOn->has_settings && $addonActive) : ?>
        <hr class="bswp__spacer">
        <a class="btn button bswp__addon__settings-toggle" href="#" data-addon="<?php echo $addOn->slug; ?>">Settings</a>
        <?php endif; ?>
      <!-- / Settings Toggle -->



      <!-- Settings -->
        <?php if ($addOn->has_settings ) : ?>
      <div class="bswp__addon__settings <?php echo $addOn->slug . '-settings'; ?>">
        <form method="post" action="<?php echo admin_url('admin.php?page=better-sharing-addons'); ?>">
          <input type="hidden" name="save_addon" value="yes" />
          <div class="bswp__addon__settings-group">
            <?php $addOn->display_settings(); ?>
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