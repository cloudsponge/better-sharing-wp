<?php

namespace BetterSharingWP\AddOns;

use BetterSharingWP\AddOnsCore;
use BetterSharingWP\OptionData;


abstract class BetterSharingAddOn
{

    public $name;
    public $slug;
    public $description;
    public $status;
    public $apiKey;
    public $hasSettings;
    public $settingsTemplatePath;
    public $optionData;
    public $supportUrl;

    /**
     * Initialize AddOn
     *
     * @param $name
     * @param $description
     * @param bool $requiresApi
     *
     * @return int|\WP_Error
     */
    public function initAddOn( $name, $description, $requiresApi = false )
    {
        $this->hasSettings = false;
        $this->name = sanitize_text_field($name);
        $this->slug = sanitize_title($name);
        $this->description = sanitize_text_field($description);
        $this->apiKey = get_site_option('_bswp_option_core_apiKey', false);

        if (! $this->apiKey && $requiresApi ) {
            return new \WP_Error('400', __('No API Key Set'));
        }

        $this->optionData = new OptionData($this->slug);
        if (! $this->optionData ) {
            return new \WP_Error('400', __('Error Creating OptionData Object'));
        }

        // Set Active State if not set.
        if (! $this->optionData->get('status') ) {
            $this->optionData->save('status', 'inactive');
        }

        $this->status = $this->optionData->get('status');

        // Add to list of addOns
        return AddOnsCore::add($this);
    }

    /**
     * Init actions
     */
    public function init()
    {
    }

    /**
     * Is AddOn Active
     *
     * @return bool
     */
    public function isActive()
    {
        return 'active' === $this->status;
    }

    /**
     * Check if related plugin is active
     *
     * @return bool
     */
    public function isPluginActive()
    {
        return true;
    }

    /**
     * Activate AddOn
     *
     * @return string
     */
    public function activate()
    {
        $this->optionData->save('status', 'active');
        $this->status = $this->optionData->get('status');
        return $this->status;
    }

    /**
     * Deactivate AddOn
     *
     * @return string
     */
    public function deactivate()
    {
        $this->optionData->save('status', 'inactive');
        $this->status = $this->optionData->get('status');
        return $this->status;
    }

    /**
     * Toggle Add On
     */
    public function toggleAddOn()
    {
        if (! $this->isActive() ) {
            $this->activate();
        } else {
            $this->deactivate();
        }
    }

    /**
     * Display Settings Template
     *
     * @return void
     */
    public function displaySettings()
    {
        if ($this->hasSettings ) {
            include_once $this->settingsTemplatePath;
        }
    }

    /**
     * Check if save add on set and true
     *
     * @return bool
     */
    public function checkIfAddOnSave()
    {
        return ! isset($_POST['save_addon']) || ( isset($_POST['save_addon']) && 'true' !== $_POST['save_addon'] );
    }

    /**
     * inject form
     */
    public function bswp_form()
    {
        include_once BETTER_SHARING_PATH . 'includes/templates/bswp-form.php';
    }

}