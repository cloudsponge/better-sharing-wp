<?php

namespace BetterSharingWP;


?>

<div class="bswp-container api-key">
    <h3>API Key</h3>
    <form action="<?php echo admin_url('admin.php?page=better-sharing-general' ); ?>" method="post">
        <table>
            <tbody>
            <tr>
                <td>
                    <input name="__bswp_api_key" type="text" id="__bswp_api_key" value="<?php echo $this->optionData->get('apiKey'); ?>" placeholder="123456789">
                </td>
                <td>
                    <input type="submit" value="Save API Key" name="__bswp_api_key__save" class="button-primary">
                </td>
            </tr>
            </tbody>
        </table>
        <p class="help">
            You can find your key in the <a target="_blank" href="https://app.cloudsponge.com/app/keys">CloudSponge Dashboard</a>
        </p>
    </form>
</div>