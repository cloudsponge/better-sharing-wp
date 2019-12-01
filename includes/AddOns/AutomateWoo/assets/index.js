import './styles.scss';

export default class AutomateWoo {

	init($) {
		this.$ = $;
		cloudsponge.init({
			afterSubmitContacts: this.afterSelect
		});
		
		$('body').on( 'click', '.add-from-address-book-init', this.clickInit );
		$('body').on('click', '.bswp-single-addon-status-toggle', this.toggleAddOn );
	}
	
	clickInit(e) {
		e.preventDefault();
		cloudsponge.launch();
	}

	afterSelect = ( contacts, source, owner ) => {
		console.log('afterSelect');
		const $wrapper = jQuery( '#referral-emails-wrapper' );
		console.log( 'before clearEmails' );
		this.clearEmails();
		console.log( 'after clearEmails' );
		contacts.forEach( (contact, key) => {
			let email = contact.email && contact.email[0] ? contact.email[0].address : false;

			if ( email ) {
				$wrapper.append('<p class="form-row form-row-wide">' +
					'<input type="email" name="emails[]" class="woocommerce-Input input-text" value="' + email + '">' +
					'</p>')
			}
		});
	}

	clearEmails = () => {
		jQuery('input[name="emails[]"]').each( ( key, value ) => {
			if ( ! jQuery(value).val() ||  '' === jQuery(value).val() ) {
				jQuery(value).parent('p.form-row').remove();
			}
		});
	};

}

(function($){

	$(document).ready(function(){
		var autoWoo = new AutomateWoo();
		autoWoo.init($);
	});

}(jQuery));