import './bs-wp-admin.scss';

const $ = jQuery;

class BSWPAdminJS {

	init = () => {
		$('body').on( 'click', '.bswp-single-addon-status-toggle', this.toggleAddOn );
		$('body').on( 'click', '.bswp-single-addon-settings-toggle', this.toggleSettings );
		$('body').on( 'click', '.copyText', this.copyText );
	};

	toggleAddOn = (e) => {
		e.preventDefault();
		const $btn = $(e.currentTarget);
		const addOn = $btn.data('addon') ? $btn.data('addon') : $btn.attr('data-addon');
		const currentStatus = $btn.data('status') ? $btn.data('status') : $btn.attr('data-status');
		const pluginActive = $btn.data('plugin');

		if ( ! addOn ) {
			return false;
		}

		if ( 'inactive' === pluginActive ) {
			alert('Plugin is not installed & activated. Go to the Plugins page to activate the appropriate plugin');
			return false;
		}

		window.location.href = window.location.href + '&toggleAddOn=true&addOn=' + addOn;
	};

	toggleSettings = (e) => {
		e.preventDefault();
		const addOn = $(e.currentTarget).data('addon');

		$('.' + addOn + '-settings').toggleClass('active');
	};

	/**
	 * Copy Text using class copyText and passing the id of the input via data attr "text"
	 * ex: <a href="#" class="copyText btn button" data-text="bswp-proxy-url">Copy</a>
	 *
	 * @param e
	 */
	copyText = (e) => {
		e.preventDefault();
		console.log('copying');
		const $btn = $(e.currentTarget);
		const preText = $btn.html();
		const textID = $btn.data('text');
		const copyText = document.getElementById(textID);
		const $copyTextElement = $('#' + textID);

		copyText.select();
		copyText.setSelectionRange(0, 99999);
		document.execCommand("copy");

		$btn.html('Copied!');
		$copyTextElement.css({'background': 'rgba(0,255,0,0.2)'});

		setTimeout(() => {
			$btn.html(preText);
			$copyTextElement.css({'background': '#eee'});
		}, 1000)
	}

}

const BSWPAdmin = new BSWPAdminJS();
$(document).ready(function(){
	BSWPAdmin.init();
});