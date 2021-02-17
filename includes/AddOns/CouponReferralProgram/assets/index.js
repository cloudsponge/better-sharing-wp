import './styles.scss';

export default class CouponReferral {

	init($) {
    this.$ = $;

    // If no cloudsponge, return.
    if (typeof cloudsponge === 'undefined' || !cloudsponge) {
      console.log('cloudsponge not defined, check API key');
      return false;
    }

    cloudsponge.init({
      referrer: 'better-sharing-wp:coupon-referral-program',
      afterSubmitContacts: this.afterSelect,
    });

    const $body = $('body');
    $body.on('click', '.add-from-address-book-init', this.clickInit);
    // $body.on( 'click', '.bswp-single-addon-status-toggle', this.toggle_addon );
    $body.on('click', '.bswp-copy', this.copyLink);
  }

	/**
	 * Init Address Book
	 *
	 * @param e
	 */
	clickInit(e) {
		e.preventDefault();

		const $emailWrapper = jQuery('#referral-emails-wrapper');

		// how many referrals should be allowed
		let maxRef = $emailWrapper.data('max') ? parseInt( $emailWrapper.data('max') ) : 5;
		const maxRefTotal = maxRef;
		let valid = 0;
		jQuery('input[name="emails[]"]').each( ( key, value ) => {
			if ( jQuery(value).val() ||  '' !== jQuery(value).val() ) {
				valid++;
			};
		});
		maxRef = maxRef - valid;

		cloudsponge.init({
			displaySelectAllNone: false,
			selectionLimit: maxRef,
			selectionLimitMessage: 'You are only allowed to refer a total of ' + maxRefTotal + ' individuals at a time.',
		});

		cloudsponge.launch();
	};

	/**
	 * After Contacts Selected
	 *
	 * @param contacts
	 * @param source
	 * @param owner
	 */
	afterSelect = ( contacts, source, owner ) => {
		console.log('afterSelect');
		const $emailField = jQuery('#bswp-share-email-input');
		let csv = $emailField.val();
		contacts.forEach( (contact, key) => {
			let email = contact.email && contact.email[0] ? contact.email[0].address : false;

			if ( email && '' !== csv ) {
				csv += ', ' + email;
			};

			if ( email && '' === csv ) {
				csv += email;
			};
		});
		$emailField.val(csv);
	};

	/**
	 * Clear Emails
	 *
	 */
	clearEmails = () => {
		jQuery('input[name="emails[]"]').each( ( key, value ) => {
			if ( ! jQuery(value).val() ||  '' === jQuery(value).val() ) {
				jQuery(value).parent('p.form-row').remove();
			};
		});
	};

	/**
	 * Copy Link - for Share Your Link section
	 *
	 * @param e
	 */
	copyLink = (e) => {
		e.preventDefault();
		const copyText = document.getElementById("bswp-coupon-referral-copy");
		copyText.select();
		copyText.setSelectionRange(0, 99999);
		document.execCommand("copy");

		const $confirmation = jQuery('.bswp-copy-confirm');
		$confirmation.show();
		setTimeout(() => {
			$confirmation.hide();
		}, 1500 );
	};

};

(function($){

	$(document).ready(function(){
		const couponref = new CouponReferral();
		couponref.init($);
	});

}(jQuery));