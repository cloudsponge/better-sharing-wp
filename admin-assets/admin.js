import './bs-wp-admin.scss';

const $ = jQuery;

class BSWPAdminJS {

	init = () => {
		$('body').on( 'click', '.bswp-single-addon-status-toggle', this.toggleAddOn );
		$('body').on( 'click', '.bswp-single-addon-settings-toggle', this.toggleSettings );
	};

	toggleAddOn = (e) => {
		e.preventDefault();
		const $btn = $(e.currentTarget);
		const addOn = $btn.data('addon') ? $btn.data('addon') : $btn.attr('data-addon');
		const currentStatus = $btn.data('status') ? $btn.data('status') : $btn.attr('data-status');

		if ( ! addOn ) {
			return false;
		}

		window.location.href = window.location.href + '&toggleAddOn=true&addOn=' + addOn;
	};

	toggleSettings = (e) => {
		e.preventDefault();
		const addOn = $(e.currentTarget).data('addon');

		$('.' + addOn + '-settings').toggleClass('active');
	}

}

const BSWPAdmin = new BSWPAdminJS();
$(document).ready(function(){
	BSWPAdmin.init();
});