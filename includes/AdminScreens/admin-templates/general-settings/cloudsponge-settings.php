<?php

namespace BetterSharingWP;


?>

<div class="bswp-container api-key">
	<h3>CloudSponge Settings</h3>
	<form action="<?php echo admin_url('admin.php?page=better-sharing-general' ); ?>" method="post">
		<table style="width:50%">
			<tbody>
			<tr>
				<td colspan="2">
					<h4>API Key</h4>
				</td>
			</tr>
			<tr>
				<td>
					<input name="__bswp_api_key" type="text" id="__bswp_api_key" value="<?php echo $this->optionData->get('apiKey'); ?>" placeholder="123456789">
				</td>
				<td>
					<input type="submit" value="Save API Key" name="__bswp_api_key__save" class="button-primary">
				</td>
			</tr>
			<?php if ( $this->optionData->get('apiKey') ) : ?>
				<tr>
					<td colspan="2"><hr/></td>
				</tr>
				<tr>
					<td>
						<strong>Your Proxy URL:</strong>
					</td>
					<td>
						<input style="width:80%;" type="text" id="bswp-proxy-url" readonly value="<?php echo BETTER_SHARING_URI .'public/proxy.html'; ?>"/>
						<a href="#" class="copyText btn button" data-text="bswp-proxy-url">Copy</a>
					</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
		<p class="help">
			You can find your key in the <a target="_blank" href="https://app.cloudsponge.com/app/keys">CloudSponge Dashboard</a>
		</p>
	</form>
</div>