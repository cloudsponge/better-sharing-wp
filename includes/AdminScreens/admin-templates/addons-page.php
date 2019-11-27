<?php

namespace BetterSharingWP;

use BetterSharingWP\AddOnsCore;

$addOns = AddOnsCore::getAddOns();

?>
<div class="bswp-container">
	<h2>AddOns</h2>
	<?php  var_dump( $addOns ); ?>
</div>