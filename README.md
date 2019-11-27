# Better Sharing WP
This WordPress plugin is to be used with your CloudSponge account.

## Requirements
* WordPress
* Composer

## Installation
* Clone Repository into `wp-content/plugins`
* Run `composer install` from `plugins/better-sharing-wp` (or whatever direcotry name you have chosen)
  
_Zip Instructions Coming Soon_

## AddOns

### Creating AddON
* Extend `BetterSharingAddOn` class
* Call `parent::init` with the name and description of your addOn

_New addOns must use base class or it will not appear in available addOns_
```
class SampleAddOn extends BetterSharingAddOn {

	public function init() {
        parent::initAddOn(
            'AutomateWoo',
            'Better Sharing WP AddOn for AutomateWoo'
        );
    }

}

$sampleAddOn = new SampleAddOn();
```