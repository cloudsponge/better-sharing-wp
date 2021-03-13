# Better Sharing WP

This WordPress plugin is to be used with your CloudSponge account.

## Requirements

- WordPress
- Node / NPM
- Composer

Tested with PHP 7.0

## Installation

- Install using one our pre-zipped releases  
  \- **OR** -
- Clone repo into `wp-content/plugins`
- Run `npm install && composer install && npm run build`
- Activate plugin via WordPress admin

## Plugin Build

Need to build the plugin to install on a WordPress site? run `npm run build:plugin` and follow the prompts

## Development

Follow Installation instructions then run `npm run start`

## AddOns

Instructions in [Wiki](https://github.com/cloudsponge/better-sharing-wp/wiki/Creating-an-AddOn)

## Shortcode

Add `[better-sharing]` to a shortcode block to render the Share-via-Email block. This will render the block with default attributes.

You may customize the output with the following arguments:
- `emailSubject` - custom email subject
- `emailMessage` - custom email message
- `twitter` - set to "false" to hide Twitter share button
- `twitterMsg` - custom default Twitter share message
- `facebook` - set to "false" to hide Facebook share button
- `emailFormControl` - set to "hide" to hide email form on frontend

All arguments are case *insensitive*.

Example: `[better-sharing emailsubject="Hey, check this out!" twittermsg="Hey followers, look at this!" facebook="false"]`